<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestDeliveryStatus extends Model
{
    public const REQUEST_CONSULTATION = 'consultation';
    public const REQUEST_PRODUCT = 'product';

    public const CHANNEL_BITRIX = 'bitrix';
    public const CHANNEL_UIS = 'uis';
    public const CHANNEL_YANDEX_METRIKA = 'yandex_metrika';

    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SENT = 'sent';
    public const STATUS_FAILED = 'failed';
    public const STATUS_UNAVAILABLE = 'unavailable';
    public const STATUS_UNCONFIRMED = 'unconfirmed';

    public const PENDING_STALE_MINUTES = 10;
    public const PROCESSING_STALE_MINUTES = 5;

    public const CHANNELS = [
        self::CHANNEL_BITRIX,
        self::CHANNEL_UIS,
        self::CHANNEL_YANDEX_METRIKA,
    ];

    public const BROWSER_CHANNELS = [
        self::CHANNEL_UIS,
        self::CHANNEL_YANDEX_METRIKA,
    ];

    public const BROWSER_STATUSES = [
        self::STATUS_SENT,
        self::STATUS_FAILED,
        self::STATUS_UNAVAILABLE,
        self::STATUS_UNCONFIRMED,
    ];

    protected $fillable = [
        'request_type',
        'request_id',
        'tracking_id',
        'channel',
        'status',
        'attempts',
        'external_id',
        'http_status',
        'error_code',
        'error_message',
        'meta',
        'started_at',
        'sent_at',
        'confirmed_at',
        'failed_at',
    ];

    protected $casts = [
        'attempts' => 'integer',
        'http_status' => 'integer',
        'meta' => 'array',
        'started_at' => 'datetime',
        'sent_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    public function statusLabel(): string
    {
        if ($this->isStaleProcessing()) {
            return 'Зависло';
        }

        if ($this->isStalePending()) {
            return 'Нет подтверждения';
        }

        return match ($this->status) {
            self::STATUS_SENT => $this->channel === self::CHANNEL_YANDEX_METRIKA ? 'Отправлено' : 'Доставлено',
            self::STATUS_FAILED => 'Ошибка',
            self::STATUS_UNAVAILABLE => $this->channel === self::CHANNEL_UIS ? 'Нет UIS' : ($this->channel === self::CHANNEL_YANDEX_METRIKA ? 'Нет ym' : 'Недоступно'),
            self::STATUS_UNCONFIRMED => 'Не подтверждено',
            self::STATUS_PROCESSING => 'Отправляется',
            self::STATUS_PENDING => 'Ожидание',
            default => 'Нет данных',
        };
    }

    public function badgeClass(): string
    {
        if ($this->isStaleProcessing()) {
            return 'bg-danger-subtle text-danger';
        }

        if ($this->isStalePending()) {
            return 'bg-warning-subtle text-warning';
        }

        return match ($this->status) {
            self::STATUS_SENT => $this->channel === self::CHANNEL_YANDEX_METRIKA ? 'bg-info-subtle text-info' : 'bg-success-subtle text-success',
            self::STATUS_FAILED => 'bg-danger-subtle text-danger',
            self::STATUS_UNAVAILABLE,
            self::STATUS_UNCONFIRMED => 'bg-warning-subtle text-warning',
            self::STATUS_PROCESSING,
            self::STATUS_PENDING => 'bg-secondary-subtle text-secondary',
            default => 'bg-light text-muted',
        };
    }

    public function isProblemForAdmin(): bool
    {
        return in_array($this->status, [
            self::STATUS_FAILED,
            self::STATUS_UNAVAILABLE,
            self::STATUS_UNCONFIRMED,
        ], true) || $this->isStalePending() || $this->isStaleProcessing();
    }

    public function isStalePending(): bool
    {
        return $this->status === self::STATUS_PENDING
            && $this->ageTimestamp()?->lte(now()->subMinutes(self::PENDING_STALE_MINUTES));
    }

    public function isStaleProcessing(): bool
    {
        return $this->status === self::STATUS_PROCESSING
            && $this->ageTimestamp()?->lte(now()->subMinutes(self::PROCESSING_STALE_MINUTES));
    }

    private function ageTimestamp()
    {
        return $this->updated_at ?? $this->created_at;
    }

    public static function channelLabel(string $channel): string
    {
        return match ($channel) {
            self::CHANNEL_BITRIX => 'BITRIX24',
            self::CHANNEL_UIS => 'UIS',
            self::CHANNEL_YANDEX_METRIKA => 'Яндекс Метрика',
            default => $channel,
        };
    }
}
