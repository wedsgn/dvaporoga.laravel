<?php

namespace App\Services;

use App\Models\RequestConsultation;
use App\Models\RequestDeliveryStatus;
use App\Models\RequestProduct;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RequestDeliveryTracker
{
    public function initialize(string $requestType, int $requestId, ?string $trackingId, array $meta = []): void
    {
        $this->guard(function () use ($requestType, $requestId, $trackingId, $meta): void {
            $now = now();
            $safeMeta = $this->sanitizeMeta($meta);
            $rows = [];

            foreach (RequestDeliveryStatus::CHANNELS as $channel) {
                $rows[] = [
                    'request_type' => $requestType,
                    'request_id' => $requestId,
                    'tracking_id' => $trackingId,
                    'channel' => $channel,
                    'status' => RequestDeliveryStatus::STATUS_PENDING,
                    'attempts' => 0,
                    'meta' => $safeMeta ? json_encode($safeMeta, JSON_UNESCAPED_UNICODE) : null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            DB::table('request_delivery_statuses')->insertOrIgnore($rows);
        }, 'initialize');
    }

    public function markProcessing(
        string $requestType,
        int $requestId,
        string $channel,
        ?string $trackingId = null,
        array $meta = []
    ): void {
        $this->recordFailOpen($requestType, $requestId, $channel, RequestDeliveryStatus::STATUS_PROCESSING, [
            'tracking_id' => $trackingId,
            'started_at' => now(),
            'meta' => $meta,
        ], true);
    }

    public function markSent(
        string $requestType,
        int $requestId,
        string $channel,
        array $data = [],
        bool $incrementAttempt = true
    ): void {
        $this->recordFailOpen($requestType, $requestId, $channel, RequestDeliveryStatus::STATUS_SENT, [
            'tracking_id' => $data['tracking_id'] ?? null,
            'external_id' => $data['external_id'] ?? null,
            'http_status' => $data['http_status'] ?? null,
            'error_code' => null,
            'error_message' => null,
            'sent_at' => now(),
            'confirmed_at' => now(),
            'failed_at' => null,
            'meta' => $data['meta'] ?? [],
        ], $incrementAttempt);
    }

    public function markFailed(
        string $requestType,
        int $requestId,
        string $channel,
        array $data = [],
        bool $incrementAttempt = true
    ): void {
        $this->recordFailOpen($requestType, $requestId, $channel, RequestDeliveryStatus::STATUS_FAILED, [
            'tracking_id' => $data['tracking_id'] ?? null,
            'external_id' => $data['external_id'] ?? null,
            'http_status' => $data['http_status'] ?? null,
            'error_code' => $data['error_code'] ?? null,
            'error_message' => $data['error_message'] ?? null,
            'failed_at' => now(),
            'meta' => $data['meta'] ?? [],
        ], $incrementAttempt);
    }

    public function markUnavailable(
        string $requestType,
        int $requestId,
        string $channel,
        array $data = [],
        bool $incrementAttempt = true
    ): void {
        $this->recordFailOpen($requestType, $requestId, $channel, RequestDeliveryStatus::STATUS_UNAVAILABLE, [
            'tracking_id' => $data['tracking_id'] ?? null,
            'error_code' => $data['error_code'] ?? null,
            'error_message' => $data['error_message'] ?? null,
            'failed_at' => now(),
            'meta' => $data['meta'] ?? [],
        ], $incrementAttempt);
    }

    public function markUnconfirmed(
        string $requestType,
        int $requestId,
        string $channel,
        array $data = [],
        bool $incrementAttempt = true
    ): void {
        $this->recordFailOpen($requestType, $requestId, $channel, RequestDeliveryStatus::STATUS_UNCONFIRMED, [
            'tracking_id' => $data['tracking_id'] ?? null,
            'error_code' => $data['error_code'] ?? null,
            'error_message' => $data['error_message'] ?? null,
            'failed_at' => now(),
            'meta' => $data['meta'] ?? [],
        ], $incrementAttempt);
    }

    public function markBrowserAck(string $trackingId, string $channel, string $status, array $data = []): bool
    {
        $trackedRequest = $this->resolveTrackedRequest($trackingId, true);

        if ($trackedRequest === null) {
            return false;
        }

        [$requestType, $requestId] = $trackedRequest;
        $data['tracking_id'] = $trackingId;

        return match ($status) {
            RequestDeliveryStatus::STATUS_SENT => $this->recordStrict($requestType, $requestId, $channel, $status, [
                'tracking_id' => $trackingId,
                'error_code' => null,
                'error_message' => null,
                'sent_at' => now(),
                'confirmed_at' => now(),
                'failed_at' => null,
                'http_status' => $data['http_status'] ?? null,
                'meta' => $data['meta'] ?? [],
            ]),
            RequestDeliveryStatus::STATUS_FAILED => $this->recordStrict($requestType, $requestId, $channel, $status, [
                'tracking_id' => $trackingId,
                'http_status' => $data['http_status'] ?? null,
                'error_code' => $data['error_code'] ?? null,
                'error_message' => $data['error_message'] ?? null,
                'failed_at' => now(),
                'meta' => $data['meta'] ?? [],
            ]),
            RequestDeliveryStatus::STATUS_UNAVAILABLE => $this->recordStrict($requestType, $requestId, $channel, $status, [
                'tracking_id' => $trackingId,
                'error_code' => $data['error_code'] ?? null,
                'error_message' => $data['error_message'] ?? null,
                'failed_at' => now(),
                'meta' => $data['meta'] ?? [],
            ]),
            RequestDeliveryStatus::STATUS_UNCONFIRMED => $this->recordStrict($requestType, $requestId, $channel, $status, [
                'tracking_id' => $trackingId,
                'error_code' => $data['error_code'] ?? null,
                'error_message' => $data['error_message'] ?? null,
                'failed_at' => now(),
                'meta' => $data['meta'] ?? [],
            ]),
            default => false,
        };
    }

    private function recordFailOpen(
        string $requestType,
        int $requestId,
        string $channel,
        string $status,
        array $data,
        bool $incrementAttempt
    ): void {
        $this->guard(function () use ($requestType, $requestId, $channel, $status, $data, $incrementAttempt): void {
            $this->record($requestType, $requestId, $channel, $status, $data, $incrementAttempt);
        }, 'record');
    }

    private function recordStrict(
        string $requestType,
        int $requestId,
        string $channel,
        string $status,
        array $data,
        bool $incrementAttempt = true
    ): bool {
        return $this->record($requestType, $requestId, $channel, $status, $data, $incrementAttempt, true);
    }

    private function record(
        string $requestType,
        int $requestId,
        string $channel,
        string $status,
        array $data,
        bool $incrementAttempt,
        bool $browserTransitionPolicy = false
    ): bool {
        $now = now();
        $row = $this->rowForInsert($requestType, $requestId, $channel, $status, $data, $incrementAttempt, $now);
        $updates = $this->updatesForStatus($status, $data, $incrementAttempt, $now, $browserTransitionPolicy);

        $query = DB::table('request_delivery_statuses')
            ->where('request_type', $requestType)
            ->where('request_id', $requestId)
            ->where('channel', $channel);

        if ($browserTransitionPolicy) {
            $this->applyBrowserTransitionPolicy($query, $status);
        } elseif ($status !== RequestDeliveryStatus::STATUS_SENT) {
            $query->where('status', '!=', RequestDeliveryStatus::STATUS_SENT);
        }

        $updated = $query->update($updates);

        if ($updated > 0) {
            return true;
        }

        DB::table('request_delivery_statuses')->insertOrIgnore($row);

        return true;
    }

    private function rowForInsert(
        string $requestType,
        int $requestId,
        string $channel,
        string $status,
        array $data,
        bool $incrementAttempt,
        mixed $now
    ): array {
        return array_merge([
            'request_type' => $requestType,
            'request_id' => $requestId,
            'tracking_id' => $data['tracking_id'] ?? null,
            'channel' => $channel,
            'status' => $status,
            'attempts' => $incrementAttempt ? 1 : 0,
            'created_at' => $now,
            'updated_at' => $now,
        ], $this->statusPayload($status, $data));
    }

    private function updatesForStatus(
        string $status,
        array $data,
        bool $incrementAttempt,
        mixed $now,
        bool $capAttemptsAtOne = false
    ): array
    {
        $updates = array_merge($this->statusPayload($status, $data), [
            'tracking_id' => $data['tracking_id'] ?? DB::raw('tracking_id'),
            'status' => $status,
            'updated_at' => $now,
        ]);

        if ($incrementAttempt) {
            $updates['attempts'] = $capAttemptsAtOne
                ? DB::raw('case when attempts < 1 then attempts + 1 else attempts end')
                : DB::raw('attempts + 1');
        }

        return $updates;
    }

    private function statusPayload(string $status, array $data): array
    {
        $payload = [
            'external_id' => $this->shortString($data['external_id'] ?? null, 100),
            'http_status' => $this->safeHttpStatus($data['http_status'] ?? null),
            'error_code' => $this->shortString($data['error_code'] ?? null, 100),
            'error_message' => $this->shortString($data['error_message'] ?? null, 500),
            'meta' => $this->encodedMeta($data['meta'] ?? []),
            'sent_at' => $data['sent_at'] ?? null,
            'confirmed_at' => $data['confirmed_at'] ?? null,
            'failed_at' => $data['failed_at'] ?? null,
        ];

        if (array_key_exists('started_at', $data)) {
            $payload['started_at'] = $data['started_at'];
        }

        if ($status === RequestDeliveryStatus::STATUS_PROCESSING) {
            $payload['started_at'] = $data['started_at'] ?? now();
        }

        if ($status === RequestDeliveryStatus::STATUS_SENT) {
            $payload['sent_at'] = $data['sent_at'] ?? now();
            $payload['confirmed_at'] = $data['confirmed_at'] ?? now();
            $payload['failed_at'] = null;
        }

        if (in_array($status, [
            RequestDeliveryStatus::STATUS_FAILED,
            RequestDeliveryStatus::STATUS_UNAVAILABLE,
            RequestDeliveryStatus::STATUS_UNCONFIRMED,
        ], true)) {
            $payload['failed_at'] = $data['failed_at'] ?? now();
        }

        return $payload;
    }

    private function applyBrowserTransitionPolicy($query, string $targetStatus): void
    {
        $allowedFrom = match ($targetStatus) {
            RequestDeliveryStatus::STATUS_UNAVAILABLE => [
                RequestDeliveryStatus::STATUS_PENDING,
                RequestDeliveryStatus::STATUS_UNAVAILABLE,
            ],
            RequestDeliveryStatus::STATUS_UNCONFIRMED => [
                RequestDeliveryStatus::STATUS_PENDING,
                RequestDeliveryStatus::STATUS_UNCONFIRMED,
            ],
            RequestDeliveryStatus::STATUS_FAILED => [
                RequestDeliveryStatus::STATUS_PENDING,
                RequestDeliveryStatus::STATUS_UNAVAILABLE,
                RequestDeliveryStatus::STATUS_UNCONFIRMED,
                RequestDeliveryStatus::STATUS_FAILED,
            ],
            RequestDeliveryStatus::STATUS_SENT => [
                RequestDeliveryStatus::STATUS_PENDING,
                RequestDeliveryStatus::STATUS_UNAVAILABLE,
                RequestDeliveryStatus::STATUS_UNCONFIRMED,
                RequestDeliveryStatus::STATUS_FAILED,
                RequestDeliveryStatus::STATUS_SENT,
            ],
            default => [],
        };

        $query->whereIn('status', $allowedFrom);
    }

    private function resolveTrackedRequest(string $trackingId, bool $strict = false): ?array
    {
        try {
            $status = RequestDeliveryStatus::where('tracking_id', $trackingId)->first();

            if ($status) {
                return [$status->request_type, (int) $status->request_id];
            }

            $consultation = RequestConsultation::where('tracking_id', $trackingId)->first(['id']);
            if ($consultation) {
                return [RequestDeliveryStatus::REQUEST_CONSULTATION, (int) $consultation->id];
            }

            $product = RequestProduct::where('tracking_id', $trackingId)->first(['id']);
            if ($product) {
                return [RequestDeliveryStatus::REQUEST_PRODUCT, (int) $product->id];
            }
        } catch (\Throwable $e) {
            if ($strict) {
                throw $e;
            }

            Log::error('Request delivery tracking lookup failed', [
                'tracking_id' => $trackingId,
                'message' => $e->getMessage(),
            ]);
        }

        return null;
    }

    private function guard(callable $callback, string $operation): void
    {
        try {
            $callback();
        } catch (\Throwable $e) {
            Log::error('Request delivery tracking failed', [
                'operation' => $operation,
                'message' => $e->getMessage(),
            ]);
        }
    }

    private function encodedMeta(array $meta): ?string
    {
        $safeMeta = $this->sanitizeMeta($meta);

        return $safeMeta ? json_encode($safeMeta, JSON_UNESCAPED_UNICODE) : null;
    }

    private function sanitizeMeta(array $meta): array
    {
        $allowed = Arr::only($meta, [
            'form_id',
            'goal',
            'visitor_id',
            'session_id',
            'hit_id',
            'callback_success',
            'info',
            'page',
            'mode',
            'trigger',
        ]);

        return $this->sanitizeArray($allowed);
    }

    private function sanitizeArray(array $values): array
    {
        $out = [];

        foreach ($values as $key => $value) {
            if (is_array($value)) {
                $out[$key] = $this->sanitizeArray($value);
                continue;
            }

            if (is_bool($value) || is_int($value) || is_float($value) || $value === null) {
                $out[$key] = $value;
                continue;
            }

            $out[$key] = $this->shortString((string) $value, 255);
        }

        return $out;
    }

    private function shortString(mixed $value, int $max): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        return mb_strlen($value) > $max ? mb_substr($value, 0, $max) : $value;
    }

    private function safeHttpStatus(mixed $value): ?int
    {
        $status = (int) $value;

        return $status >= 100 && $status <= 599 ? $status : null;
    }
}
