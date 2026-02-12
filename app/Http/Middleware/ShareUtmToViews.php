<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Support\Utm;

class ShareUtmToViews
{
    public function handle(Request $request, Closure $next)
    {
        // Важно: тут уже отработает EncryptCookies и cookie будет читаться корректно
        View::share('utm', Utm::payload($request));

        return $next($request);
    }
}
