<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class YandexFeedController extends Controller
{
    public function __invoke()
    {
        $path = storage_path('app/feeds/yandex.yml');

        abort_unless(file_exists($path), 404);

        return response()->file(
            $path,
            ['Content-Type' => 'application/xml; charset=UTF-8']
        );
    }
}
