<?php

namespace App\Jobs;

use App\Models\ImportRun;
use App\Services\CatalogSpreadsheetReader;
use App\Services\ImportLogger;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CatalogImportStartJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $runId) {}

    public function handle(CatalogSpreadsheetReader $reader): void
    {
        $run = ImportRun::findOrFail($this->runId);

        if (in_array($run->status, ['done','canceled'], true)) return;
        if ($run->status === 'paused') return;

        $run->status = 'running';
        $run->started_at ??= now();
        $run->last_error = null;
        $run->save();

        $abs = storage_path('app/' . $run->stored_path);

        if ($run->total_rows <= 0) {
            $run->total_rows = $reader->countDataRows($abs);
            $run->save();
        }

        ImportLogger::info($run, 'Импорт запущен', [
            'total_rows' => $run->total_rows,
            'current_row' => $run->current_row,
            'chunk_size' => $run->chunk_size,
        ]);

        CatalogImportChunkJob::dispatch($run->id)->onQueue('imports');
    }
}
