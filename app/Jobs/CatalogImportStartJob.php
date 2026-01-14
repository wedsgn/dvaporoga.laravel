<?php

namespace App\Jobs;

use App\Models\ImportRun;
use App\Services\CatalogSpreadsheetReader;
use App\Services\ImportLogger;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;

class CatalogImportStartJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $runId) {}

    public function middleware(): array
    {
        return [
            (new WithoutOverlapping('catalog-import-start-' . $this->runId))
                ->releaseAfter(10),
        ];
    }

    public function handle(CatalogSpreadsheetReader $reader): void
    {
        $run = ImportRun::findOrFail($this->runId);

        // already finished / stopped
        if (in_array($run->status, ['done', 'canceled'], true)) return;
        if ($run->status === 'paused') return;

        $run->status = 'running';
        $run->started_at ??= now();
        $run->last_error = null;
        $run->heartbeat_at = now();
        $run->save();

        $abs = storage_path('app/' . $run->stored_path);

        // 1) total rows
        if ((int) $run->total_rows <= 0) {
            $run->total_rows = $reader->countDataRows($abs);
            $run->save();
        }

        /**
         * 2) detail columns map
         * ВАЖНО: пересобираем КАЖДЫЙ запуск.
         * Иначе ты один раз сохранил неправильную карту и дальше хоть код меняй —
         * импорт будет использовать старое значение из БД.
         */
        $h1 = array_values($reader->readFirstHeaderRowResolved($abs));
        $h2 = array_values($reader->readSecondHeaderRow($abs));

        $detailColumns = $this->buildDetailColumns($h1, $h2);

        $run->detail_columns = $detailColumns;
        $run->save();

        ImportLogger::info($run, 'Карта деталей построена (пересобрана)', [
            'count' => count($detailColumns),
            // 'sample' => array_slice($detailColumns, 0, 80, true),
        ]);

        ImportLogger::info($run, 'Импорт запущен', [
            'total_rows'   => (int) $run->total_rows,
            'current_row'  => (int) $run->current_row,
            'chunk_size'   => (int) $run->chunk_size,
        ]);

        CatalogImportChunkJob::dispatch($run->id)->onQueue('imports');
    }

    /**
     * Build map: excelColumnIndex (0-based) => detail title
     * Uses:
     *  - $h1 = row 1 group headers (resolved by merges; empty outside merge == no group)
     *  - $h2 = row 2 sub headers (detail names)
     */
    private function buildDetailColumns(array $h1, array $h2): array
    {
        $out = [];

        // find column "Кузов" in second header row to start details after it
        $bodyIdx = null;
        foreach ($h2 as $i => $v) {
            $name = trim((string) $v);
            if (mb_strtolower($name) === 'кузов') {
                $bodyIdx = (int) $i;
                break;
            }
        }

        // if not found, fall back to old behavior
        $start = $bodyIdx !== null ? ($bodyIdx + 1) : 6;

        // last meaningful column (either header row has something)
        $last = 0;
        $max = max(count($h1), count($h2));
        for ($i = $max - 1; $i >= 0; $i--) {
            $v1 = trim((string) ($h1[$i] ?? ''));
            $v2 = trim((string) ($h2[$i] ?? ''));
            if ($v1 !== '' || $v2 !== '') {
                $last = $i;
                break;
            }
        }

        for ($i = 0; $i <= $last; $i++) {
            if ($i < $start) continue;

            // row1 group for this exact column (already resolved by merge; no stretching!)
            $group = trim((string) ($h1[$i] ?? ''));
            // row2 sub-title for this exact column
            $n = trim((string) ($h2[$i] ?? ''));

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

            // excel column index (0-based) -> title
            $out[$i] = $title;
        }

        return $out;
    }
}
