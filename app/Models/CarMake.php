<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CarMake extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'image',
        'image_mob',
        'description'
    ];
    public static $car_makes_routes = [
      'admin.car_makes.index',
      'admin.car_makes.search',
      'admin.car_makes.show',
      'admin.car_makes.edit',
      'admin.car_makes.create'
    ];
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function car_models()
    {
        return $this->hasMany(CarModel::class);
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
