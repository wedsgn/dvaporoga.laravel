<?php

use App\Http\Middleware\RebuildYandexFeedIfDirty;
use App\Http\Middleware\StoreUtm;
use App\Http\Middleware\ShareUtmToViews;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // StoreUtm можно prepend — он только читает query и ставит cookie в response
        $middleware->web(prepend: [
            StoreUtm::class,
        ]);

        // ShareUtmToViews должен быть append, чтобы cookie уже были доступны корректно
        $middleware->web(append: [
            ShareUtmToViews::class,
        ]);

        $middleware->append(RebuildYandexFeedIfDirty::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
