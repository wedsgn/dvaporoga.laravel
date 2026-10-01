<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class RequestProduct extends Model
{
    use HasFactory,Notifiable;
    protected $fillable = [
      'name',
      'phone',
      'form_id',
      'data',
      'total_price',
      'car'
  ];
  public static $request_products_routes = [
    'admin.request_products.index',
    'admin.request_products.search',
    'admin.request_products.show',
    'admin.request_products.edit',
    'admin.request_products.create'
  ];

  protected static function booted(): void
  {
      static::creating(function (RequestProduct $requestProduct): void {
          if (empty($requestProduct->tracking_id)) {
              $requestProduct->tracking_id = (string) Str::uuid();
          }
      });
  }

  public function deliveryStatuses(): HasMany
  {
      return $this->hasMany(RequestDeliveryStatus::class, 'request_id')
          ->where('request_type', RequestDeliveryStatus::REQUEST_PRODUCT);
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
