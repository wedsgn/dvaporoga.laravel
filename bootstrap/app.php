<?php

use App\Http\Middleware\RebuildYandexFeedIfDirty;
use App\Http\Middleware\StoreUtm;
use App\Http\Middleware\ShareUtmToViews;
use App\Support\UploadValidation;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;

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
        $exceptions->render(function (PostTooLargeException $e, Request $request) {
            $message = UploadValidation::postTooLargeMessage();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => $message,
                    'errors' => [
                        'file' => [$message],
                    ],
                ], 413);
            }

            return back()
                ->withInput($request->except(['file', 'image', 'image_mob', 'image_desktop', 'image_mobile', 'upload']))
                ->withErrors(['file' => $message]);
        });
    })->create();
