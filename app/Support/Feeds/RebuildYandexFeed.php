<?php

namespace App\Support\Feeds;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class RebuildYandexFeed
{
    public static function run(): void
    {
        $lock = cache()->lock('yandex_feed_build_lock', 60);

        if (!$lock->get()) {
            return;
        }

        try {
            Artisan::call('feed:yandex');
        } catch (\Throwable $e) {
            Log::error('Yandex feed rebuild failed: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
        } finally {
            optional($lock)->release();
        }
    }
}
