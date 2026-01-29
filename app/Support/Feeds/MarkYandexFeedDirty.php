<?php

namespace App\Support\Feeds;

use Illuminate\Support\Facades\Cache;

class MarkYandexFeedDirty
{
    public static function mark(): void
    {
        Cache::put('yandex_feed_dirty', true, 3600);
    }
}
