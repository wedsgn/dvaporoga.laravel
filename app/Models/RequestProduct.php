<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestProduct extends Model
{
    use HasFactory;
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
