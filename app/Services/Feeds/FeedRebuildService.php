<?php

namespace App\Services\Feeds;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FeedRebuildService
{
    public function rebuildYandex(): void
    {
        $lock = Cache::lock('yandex_feed_build_lock', 120);

        if (!$lock->get()) {
            return;
        }

        try {
            app(YandexFeedBuilder::class)->build();
        } catch (\Throwable $e) {
            Log::error('Yandex feed rebuild failed: ' . $e->getMessage(), ['exception' => $e]);
        } finally {
            optional($lock)->release();
        }
    }
}
