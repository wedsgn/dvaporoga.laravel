<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Car extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
      'title',
      'slug',
      'generation',
      'years',
      'body',
      'artikul',
      'car_model_id',
      'image',
      'image_mob',
      'description'
  ];
  public static $cars_routes = [
    'admin.cars.index',
    'admin.cars.search',
    'admin.cars.show',
    'admin.cars.edit',
    'admin.cars.create'
  ];
  public function getRouteKeyName()
  {
      return 'slug';
  }
  public function products()
  {
      return $this->belongsToMany(Product::class);
  }
  public function car_model()
  {
      return $this->belongsTo(CarModel::class);
  }

  public function scopeFilter($items)
  {
      if (request('search') !== null) {
          $items->where('id', 'ilike', '%' . request('search') . '%')
          ->orWhere('slug', 'ilike', '%' . request('search') . '%')
          ->orWhere('title', 'ilike', '%' . request('search') . '%');
      }
      return $items;
  }

  // public function delete_files($item)
  // {
  //     if( $item->image):
  //         $path_to_file = Str::remove(env('APP_URL') . '/storage', $item->image);
  //         Storage::disk('public')->delete($path_to_file);
  //     endif;
  //     if( $item->image_mob):
  //         $path_to_file = Str::remove(env('APP_URL') . '/storage', $item->image_mob);
  //         Storage::disk('public')->delete($path_to_file);
  //     endif;
  // }
}

