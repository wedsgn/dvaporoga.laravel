<?php

namespace App\Services\Forms;

use Illuminate\Support\Facades\Cache;

class FormSubmissionCooldownService
{
    public const TTL_SECONDS = 1800; // 30 минут

    public function acquire(?string $phone): bool
    {
        $normalized = $this->normalizePhone($phone);

        if ($normalized === null) {
            return true;
        }

        $expiresAt = now()->addSeconds(self::TTL_SECONDS)->timestamp;

        return Cache::add(
            $this->key($normalized),
            $expiresAt,
            now()->addSeconds(self::TTL_SECONDS)
        );
    }

    public function retryAfter(?string $phone): int
    {
        $normalized = $this->normalizePhone($phone);

        if ($normalized === null) {
            return 0;
        }

        $expiresAt = (int) Cache::get($this->key($normalized), 0);

        return max(0, $expiresAt - now()->timestamp);
    }

    public function release(?string $phone): void
    {
        $normalized = $this->normalizePhone($phone);

        if ($normalized === null) {
            return;
        }

        Cache::forget($this->key($normalized));
    }

    private function key(string $normalizedPhone): string
    {
        return 'forms:cooldown:' . $normalizedPhone;
    }

    private function normalizePhone(?string $phone): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone);

        if ($digits === '') {
            return null;
        }

        if (strlen($digits) === 11 && str_starts_with($digits, '8')) {
            $digits = '7' . substr($digits, 1);
        }

        if (strlen($digits) === 10) {
            $digits = '7' . $digits;
        }

        return $digits;
    }
}
