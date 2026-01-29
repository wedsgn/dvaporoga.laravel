<?php

namespace App\Jobs;

use App\Models\ImportRun;
use App\Models\Product;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\CarMake;
use App\Services\CatalogRowProcessor;
use App\Services\CatalogSpreadsheetReader;
use App\Services\ImportLogger;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Schema;

class CatalogImportChunkJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public int $tries = 1;

  public function __construct(public int $runId) {}

  public function middleware(): array
  {
    return [
      (new WithoutOverlapping('catalog-import-run-' . $this->runId))
        ->releaseAfter(10),
    ];
  }

  public function handle(
    CatalogSpreadsheetReader $reader,
    CatalogRowProcessor $processor
  ): void {
    $run = ImportRun::findOrFail($this->runId);

    if ($run->status !== 'running') {
      return;
    }

    $abs = storage_path('app/' . $run->stored_path);

    // detail columns map (из run или построение на лету)
    if (!empty($run->detail_columns) && is_array($run->detail_columns)) {
      $detailColumns = $run->detail_columns;
    } else {
      // IMPORTANT: group row1 must be resolved by merge ranges
      $h1 = array_values($reader->readFirstHeaderRowResolved($abs));
      $h2 = array_values($reader->readSecondHeaderRow($abs));
      $detailColumns = $this->buildDetailColumns($h1, $h2);

      ImportLogger::warn($run, 'detail_columns отсутствует в run — построено на лету', [
        'count' => count($detailColumns),
      ]);
    }

    $offset = (int)$run->current_row; // 0-based data offset
    $limit  = (int)$run->chunk_size;

    $chunk = $reader->readChunk($abs, $offset, $limit);

    // --------- КОНЕЦ ФАЙЛА (пустой чанк) ----------
    if (empty($chunk)) {
      $run->status = 'done';
      $run->finished_at = now();
      $run->save();

      $this->cleanupStale($run);
      \App\Support\Feeds\MarkYandexFeedDirty::mark();
      \App\Support\Feeds\RebuildYandexFeed::run();

      ImportLogger::info($run, 'Импорт завершён');
      return;
    }

    $processedInThisChunk = 0;
    $checkEvery = 10;

    foreach ($chunk as $idx => $row) {
      if ($processedInThisChunk > 0 && ($processedInThisChunk % $checkEvery) === 0) {
        $run->refresh();
        if ($run->status !== 'running') {
          $this->applyProgress($run, $offset, $processedInThisChunk);
          ImportLogger::info($run, 'Остановлено внутри чанка (пауза/стоп)', [
            'from' => $offset,
            'done_in_chunk' => $processedInThisChunk,
          ]);
          return;
        }
      }

      $dataRowNumber  = $offset + $idx;
      $excelRowNumber = 3 + $dataRowNumber;

      try {
        $processor->processRow($run, $row, $detailColumns, [
          'data_row'  => $dataRowNumber,
          'excel_row' => $excelRowNumber,
        ]);

        $processedInThisChunk++;
      } catch (QueryException $e) {
        $run->status = 'failed';
        $run->last_error = $e->getMessage();
        $run->save();

        ImportLogger::error($run, 'Ошибка БД на строке', [
          'excel_row' => $excelRowNumber,
          'message' => $e->getMessage(),
        ]);
        return;
      } catch (\Throwable $e) {
        $run->status = 'failed';
        $run->last_error = $e->getMessage();
        $run->save();

        ImportLogger::error($run, 'Ошибка обработки строки', [
          'excel_row' => $excelRowNumber,
          'message' => $e->getMessage(),
        ]);
        return;
      }
    }

    $this->applyProgress($run, $offset, $processedInThisChunk);

    ImportLogger::info($run, 'Обработан чанк', [
      'from' => $offset,
      'count' => $processedInThisChunk,
      'processed' => $run->processed_rows,
      'total' => $run->total_rows,
    ]);

    // --------- ДОШЛИ ДО КОНЦА ----------
    if ($run->processed_rows >= $run->total_rows || $run->current_row >= $run->total_rows) {
      $run->status = 'done';
      $run->finished_at = now();
      $run->save();

      $this->cleanupStale($run);
      \App\Support\Feeds\MarkYandexFeedDirty::mark();
      \App\Support\Feeds\RebuildYandexFeed::run();
      ImportLogger::info($run, 'Импорт завершён');
      return;
    }

    $run->refresh();
    if ($run->status !== 'running') {
      ImportLogger::info($run, 'Следующий чанк НЕ запланирован (пауза/стоп)');
      return;
    }

    CatalogImportChunkJob::dispatch($run->id)->onQueue('imports');
  }

  private function applyProgress(ImportRun $run, int $offset, int $processedInThisChunk): void
  {
    $run->refresh();
    $run->processed_rows = min((int)$run->total_rows, (int)$run->processed_rows + $processedInThisChunk);
    $run->current_row = $offset + $processedInThisChunk;
    $run->save();
  }

  /**
   * То же построение, что и в StartJob.
   */
  private function buildDetailColumns(array $h1, array $h2): array
  {
    $out = [];

    $bodyIdx = null;
    foreach ($h2 as $i => $v) {
      $name = trim((string)$v);
      if (mb_strtolower($name) === 'кузов') {
        $bodyIdx = (int)$i;
        break;
      }
    }

    $start = $bodyIdx !== null ? ($bodyIdx + 1) : 6;

    $last = 0;
    $max = max(count($h1), count($h2));
    for ($i = $max - 1; $i >= 0; $i--) {
      $v1 = trim((string)($h1[$i] ?? ''));
      $v2 = trim((string)($h2[$i] ?? ''));
      if ($v1 !== '' || $v2 !== '') {
        $last = $i;
        break;
      }
    }

    for ($i = 0; $i <= $last; $i++) {
      if ($i < $start) continue;

      $group = trim((string)($h1[$i] ?? ''));
      $n = trim((string)($h2[$i] ?? ''));

      if ($n === '' && $group === '') continue;

      if ($n !== '' && $group !== '') {
        $title = (mb_strtolower($n) === mb_strtolower($group))
          ? $n
          : ($group . ' ' . $n);
      } else {
        $title = $n !== '' ? $n : $group;
      }

      $title = trim(preg_replace('~\s+~u', ' ', $title));
      if ($title === '') continue;

      $out[$i] = $title;
    }

    return $out;
  }

  /**
   * Удаляем всё, что НЕ было затронуто текущим импортом.
   * Важно: используем SoftDeletes (delete()).
   * Порядок: products -> cars -> car_models -> car_makes.
   */
  private function cleanupStale(ImportRun $run): void
  {
    $runId = (int)$run->id;

    $tables = [
      'products' => Product::class,
      'cars' => Car::class,
      'car_models' => CarModel::class,
      'car_makes' => CarMake::class,
    ];

    foreach ($tables as $table => $modelClass) {
      if (!Schema::hasColumn($table, 'last_import_run_id')) {
        ImportLogger::warn($run, "Cleanup пропущен: нет колонки last_import_run_id в {$table}");
        return;
      }
    }

    // products — под импорт (обычно у импортных есть car_id)
    Product::query()
      ->whereNotNull('car_id')
      ->where(function ($q) use ($runId) {
        $q->whereNull('last_import_run_id')
          ->orWhere('last_import_run_id', '!=', $runId);
      })
      ->delete();

    Car::query()
      ->where(function ($q) use ($runId) {
        $q->whereNull('last_import_run_id')
          ->orWhere('last_import_run_id', '!=', $runId);
      })
      ->delete();

    CarModel::query()
      ->where(function ($q) use ($runId) {
        $q->whereNull('last_import_run_id')
          ->orWhere('last_import_run_id', '!=', $runId);
      })
      ->delete();

    CarMake::query()
      ->where(function ($q) use ($runId) {
        $q->whereNull('last_import_run_id')
          ->orWhere('last_import_run_id', '!=', $runId);
      })
      ->delete();

    ImportLogger::info($run, 'Cleanup завершён: удалены записи, отсутствующие в таблице импорта', [
      'run_id' => $runId,
    ]);
  }

  public function failed(\Throwable $e): void
  {
    $run = \App\Models\ImportRun::find($this->runId);
    if (!$run) return;

    $run->status = 'failed';
    $run->last_error = $e->getMessage();
    $run->save();

    \App\Services\ImportLogger::error($run, 'Chunk job упал', [
      'message' => $e->getMessage(),
    ]);
  }
}
