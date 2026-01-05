<?php

namespace App\Jobs;

use App\Models\ImportRun;
use App\Services\CatalogRowProcessor;
use App\Services\CatalogSpreadsheetReader;
use App\Services\ImportLogger;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CatalogImportChunkJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public int $tries = 1; // чтобы не повторять автоматически кусок без контроля

  public function __construct(public int $runId) {}

  public function handle(
    CatalogSpreadsheetReader $reader,
    CatalogRowProcessor $processor
  ): void {
    $run = ImportRun::findOrFail($this->runId);

    if ($run->status !== 'running') {
      return; // paused/failed/done/canceled
    }

    $abs = storage_path('app/' . $run->stored_path);

    // читаем заголовок деталей (2-я строка) и строим мапу колонок
    $header2 = $reader->readSecondHeaderRow($abs);
    $detailColumns = $this->buildDetailColumns($header2);

    $offset = $run->current_row; // 0-based data offset
    $limit  = (int)$run->chunk_size;

    $chunk = $reader->readChunk($abs, $offset, $limit);

    if (empty($chunk)) {
      $run->status = 'done';
      $run->finished_at = now();
      $run->save();
      ImportLogger::info($run, 'Импорт завершён');
      return;
    }

    $processedInThisChunk = 0;

    foreach ($chunk as $idx => $row) {
      $dataRowNumber = $offset + $idx; // 0-based по данным
      $excelRowNumber = 3 + $dataRowNumber; // т.к. 2 header rows

      try {
        $processor->processRow($run, $row, $detailColumns, [
          'data_row' => $dataRowNumber,
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

    // обновляем прогресс (важно делать атомарно)
    $run->processed_rows = min($run->total_rows, $run->processed_rows + $processedInThisChunk);
    $run->current_row = $offset + $processedInThisChunk;
    $run->save();

    ImportLogger::info($run, 'Обработан чанк', [
      'from' => $offset,
      'count' => $processedInThisChunk,
      'processed' => $run->processed_rows,
      'total' => $run->total_rows,
    ]);

    // если дошли до конца
    if ($run->processed_rows >= $run->total_rows || $run->current_row >= $run->total_rows) {
      $run->status = 'done';
      $run->finished_at = now();
      $run->save();
      ImportLogger::info($run, 'Импорт завершён');
      return;
    }

    // планируем следующий чанк
    CatalogImportChunkJob::dispatch($run->id)->onQueue('imports');
  }

  private function buildDetailColumns(array $headerRow2): array
  {
    // у тебя 6..14 — детали
    $out = [];
    for ($i = 6; $i <= 14; $i++) {
      $name = trim((string)($headerRow2[$i] ?? ''));
      if ($name !== '') $out[$i] = $name;
    }
    return $out;
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
