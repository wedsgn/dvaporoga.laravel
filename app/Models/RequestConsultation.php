<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class RequestConsultation extends Model
{
    use HasFactory,Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'form_id',
    ];
    public static $request_consultations_routes = [
      'admin.request_consultations.index',
      'admin.request_consultations.search',
      'admin.request_consultations.show',
      'admin.request_consultations.edit',
      'admin.request_consultations.create'
    ];

    protected static function booted(): void
    {
        static::creating(function (RequestConsultation $requestConsultation): void {
            if (empty($requestConsultation->tracking_id)) {
                $requestConsultation->tracking_id = (string) Str::uuid();
            }
        });
    }

    public function deliveryStatuses(): HasMany
    {
        return $this->hasMany(RequestDeliveryStatus::class, 'request_id')
            ->where('request_type', RequestDeliveryStatus::REQUEST_CONSULTATION);
    }

    public function deliveryStatusFor(string $channel): ?RequestDeliveryStatus
    {
        return $this->deliveryStatuses->firstWhere('channel', $channel);
    }

    public function scopeFilter($items)
    {
        if (request('search') !== null) {
            $items->where('id', 'ilike', '%' . request('search') . '%')
            ->orWhere('phone', 'ilike', '%' . request('search') . '%')
            ->orWhere('name', 'ilike', '%' . request('search') . '%');
        }
        return $items;
    }
}

