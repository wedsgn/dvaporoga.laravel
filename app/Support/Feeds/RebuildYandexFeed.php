<?php

namespace App\Support\Feeds;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RebuildYandexFeed
{
    public static function run(bool $force = false): bool
    {
        // 1) если не dirty и не форс — не делаем ничего
        if (!$force && !Cache::get('yandex_feed_dirty')) {
            Log::info('RebuildYandexFeed: skip (not dirty)');
            return false;
        }

        $lock = Cache::lock('yandex_feed_build_lock', 600);

        if (!$lock->get()) {
            Log::warning('RebuildYandexFeed: skip (lock busy)');
            return false;
        }

        try {
            Log::info('RebuildYandexFeed: build start');
            Artisan::call('feed:yandex');
            Log::info('RebuildYandexFeed: build done', [
                'exit_code' => Artisan::output(),
            ]);

            Cache::forget('yandex_feed_dirty');

            return true;
        } catch (\Throwable $e) {
            Log::error('Yandex feed rebuild failed: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            return false;
        } finally {
            optional($lock)->release();
        }
    }
}
