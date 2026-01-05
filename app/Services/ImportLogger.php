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

    private static function write(ImportRun $run, string $level, string $message, array $context): void
    {
        ImportLog::create([
            'import_run_id' => $run->id,
            'level' => $level,
            'message' => $message,
            'context' => $context ?: null,
        ]);
    }
}
