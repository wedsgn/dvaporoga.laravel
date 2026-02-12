<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StoreUtm
{
    private array $keys = [
        'utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term',
        'cm_id',
    ];

    public function handle(Request $request, Closure $next)
    {
        // Берём UTM из query
        $incoming = $request->only($this->keys);
        $incoming = array_filter($incoming, static fn ($v) => $v !== null && $v !== '');

        // Если есть метки — ставим cookie ДО $next()
        if (!empty($incoming)) {
            $json  = json_encode($incoming, JSON_UNESCAPED_UNICODE);

            // base64url (безопасно для cookie)
            $value = rtrim(strtr(base64_encode($json), '+/', '-_'), '=');

            cookie()->queue(cookie(
                'utm_payload',
                $value,
                60 * 24 * 30,       // 30 дней
                '/',                // path
                null,               // domain (если есть www/поддомены — поставь '.dvaporoga.ru')
                $request->isSecure(), // secure (https=true)
                false,              // httpOnly (чтобы можно было читать в JS при желании)
                false,              // raw
                'Lax'               // samesite
            ));

            \Log::info('StoreUtm: queued', [
                'url' => $request->fullUrl(),
                'incoming' => $incoming,
                'cookie_value_len' => strlen($value),
                'host' => $request->getHost(),
                'secure' => $request->isSecure(),
            ]);
        }

        return $next($request);
    }
}
