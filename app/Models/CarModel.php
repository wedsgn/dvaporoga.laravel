<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CarModel extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
      'title',
      'slug',
      'image',
      'image_mob',
      'description',
      'car_make_id'
  ];
  public static $car_models_routes = [
    'admin.car_models.index',
    'admin.car_models.search',
    'admin.car_models.show',
    'admin.car_models.edit',
    'admin.car_models.create'
  ];
  public function getRouteKeyName()
  {
      return 'slug';
  }

  public function cars()
  {
      return $this->hasMany(Car::class);
  }
  public function car_make()
  {
      return $this->belongsTo(CarMake::class);
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
