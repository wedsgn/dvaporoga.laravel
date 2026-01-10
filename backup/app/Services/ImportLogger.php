<?php

namespace App\Services;

use App\Models\ImportLog;
use App\Models\ImportRun;

class ImportLogger
{
    public static function info(ImportRun $run, string $message, array $context = []): void
    {
        self::write($run, 'info', $message, $context);
    }

    public static function warn(ImportRun $run, string $message, array $context = []): void
    {
        self::write($run, 'warn', $message, $context);
    }

    public static function error(ImportRun $run, string $message, array $context = []): void
    {
        self::write($run, 'error', $message, $context);
    }

    public static function write(ImportRun $run, string $level, string $message, array $context = []): void
    {
        ImportLog::query()->create([
            'import_run_id' => $run->id,
            'level' => $level,
            'message' => $message,
            'context' => $context ?: null,
        ]);
    }

    /**
     * Вернуть последние N логов для run (в правильном порядке: сверху старые -> снизу новые)
     */
    public static function tail(ImportRun $run, int $limit = 200): array
    {
        $rows = ImportLog::query()
            ->where('import_run_id', $run->id)
            ->orderByDesc('id')
            ->limit($limit)
            ->get(['id', 'level', 'message', 'context', 'created_at']);

        if ($rows->isEmpty()) return [];

        // разворачиваем, чтобы было по времени
        $rows = $rows->reverse()->values();

        $out = [];
        foreach ($rows as $r) {
            $ts = $r->created_at ? $r->created_at->toISOString() : null;

            // контекст выводим коротко (без простыней)
            $ctx = '';
            if (!empty($r->context) && is_array($r->context)) {
                $mini = $r->context;

                // часто полезные поля вытаскиваем первыми
                $keep = [];
                foreach (['excel_row','data_row','make','model','generation','car_id','product_id'] as $k) {
                    if (array_key_exists($k, $mini)) $keep[$k] = $mini[$k];
                }
                if ($keep) $mini = $keep + $mini;

                $ctx = ' ' . json_encode($mini, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }

            $out[] = sprintf('[%s] %s: %s%s', $ts, strtoupper($r->level), $r->message, $ctx);
        }

        return $out;
    }

    /**
     * Очистить логи для run
     */
    public static function clear(ImportRun $run): void
    {
        ImportLog::query()
            ->where('import_run_id', $run->id)
            ->delete();
    }
}
