<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use App\Support\Feeds\RebuildYandexFeed;

class RebuildYandexFeedIfDirty
{
  public function handle($request, Closure $next)
  {
    return $next($request);
  }

  public function terminate($request, $response): void
  {
    if (!Cache::get('yandex_feed_dirty')) {
      return;
    }

    if (RebuildYandexFeed::run()) {
      Cache::forget('yandex_feed_dirty');
    }
  }
}
