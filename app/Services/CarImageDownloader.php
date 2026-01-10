<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class CarImageDownloader
{
    // лимиты
    private int $timeout = 12;       // сек
    private int $connectTimeout = 5; // сек
    private int $maxBytes = 8_000_000; // 8MB

    public function downloadToCacheWebp(string $url): ?string
    {
        $url = trim($url);
        if ($url === '' || $url === '1') return null;
        if (!preg_match('~^https?://~i', $url)) return null;

        $hash = sha1($url);
        $path = "uploads/cache/car-images/{$hash}.webp";

        if (Storage::disk('public')->exists($path)) {
            return $path;
        }

        try {
            // HEAD (если доступен) — чтобы не тянуть гигантские файлы
            $head = Http::timeout($this->timeout)
                ->connectTimeout($this->connectTimeout)
                ->withOptions(['allow_redirects' => true])
                ->withHeaders(['User-Agent' => 'Mozilla/5.0'])
                ->head($url);

            if ($head->ok()) {
                $len = (int)($head->header('Content-Length') ?? 0);
                if ($len > 0 && $len > $this->maxBytes) return null;

                $ct = (string)($head->header('Content-Type') ?? '');
                if ($ct !== '' && stripos($ct, 'image/') === false) {
                    // иногда сервера врут, но если явно не image — пропускаем
                    return null;
                }
            }

            $resp = Http::timeout($this->timeout)
                ->connectTimeout($this->connectTimeout)
                ->retry(2, 500)
                ->withOptions(['allow_redirects' => true])
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                    'Accept' => 'image/avif,image/webp,image/apng,image/*,*/*;q=0.8',
                ])
                ->get($url);

            if (!$resp->successful()) return null;

            $bytes = $resp->body();
            if (!$bytes || strlen($bytes) < 200) return null;
            if (strlen($bytes) > $this->maxBytes) return null;

            // конвертим в webp
            $img = Image::read($bytes)->toWebp(80);

            Storage::disk('public')->put($path, (string)$img);

            return $path;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
