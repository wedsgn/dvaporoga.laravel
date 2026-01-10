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
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Schema;

class CatalogImportChunkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public function __construct(public int $runId) {}

    public function handle(
        CatalogSpreadsheetReader $reader,
        CatalogRowProcessor $processor
    ): void {
        $run = ImportRun::findOrFail($this->runId);

        if ($run->status !== 'running') {
            return;
        }

        $abs = storage_path('app/' . $run->stored_path);

        $detailColumns = [];
        if (!empty($run->detail_columns) && is_array($run->detail_columns)) {
            $detailColumns = $run->detail_columns;
        } else {
            $h1 = $reader->readFirstHeaderRow($abs);
            $h2 = $reader->readSecondHeaderRow($abs);
            $header2 = $h2;
            $detailColumns = $this->buildDetailColumnsFallback($header2);
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

    private function buildDetailColumnsFallback(array $headerRow2): array
    {
        $out = [];
        for ($i = 6; $i <= 200; $i++) {
            $name = trim((string)($headerRow2[$i] ?? ''));
            if ($name !== '') $out[$i] = $name;
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

        // если колонок нет — cleanup невозможен
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
