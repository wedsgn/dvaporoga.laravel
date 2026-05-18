<?php

namespace App\Http\Controllers;

use App\Services\Feeds\YandexFeedBuilder;

class YandexFeedController extends Controller
{
    public function __invoke(YandexFeedBuilder $builder)
    {
        $path = storage_path('app/feeds/yandex.yml');

        if (!file_exists($path)) {
            $builder->build();
        }

        abort_unless(file_exists($path), 404);

        return response()->file($path, [
            'Content-Type' => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
