<?php

namespace App\Support;

use Illuminate\Http\Request;

class Utm
{
    public static function keys(): array
    {
        return [
            'utm_source','utm_medium','utm_campaign','utm_content','utm_term','cm_id',
        ];
    }

    public static function payload(Request $request): array
    {
        // 1) Сначала берём из URL
        $incoming = $request->only(self::keys());
        $incoming = array_filter($incoming, static fn ($v) => $v !== null && $v !== '');

        if (!empty($incoming)) {
            return $incoming;
        }

        // 2) Если в URL нет — берём из cookie (base64url -> json)
        $raw = $request->cookie('utm_payload');
        if (!$raw) return [];

        // base64url -> base64
        $raw = strtr($raw, '-_', '+/');
        $pad = strlen($raw) % 4;
        if ($pad) {
            $raw .= str_repeat('=', 4 - $pad);
        }

        $json = base64_decode($raw, true);
        if ($json === false) return [];

        $data = json_decode($json, true);

        return is_array($data) ? $data : [];
    }
}
