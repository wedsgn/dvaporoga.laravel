<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Transliterator;

class Car extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
      'title',
      'slug',
      'generation',
      'years',
      'body',
      'top',
      'artikul',
      'car_model_id',
      'image',
      'image_mob',
      'description',
      'meta_title',
      'meta_description',
      'meta_keywords',
      'og_url',
      'og_title',
      'og_description'
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
      $search = request('search');
      $search = mb_strtolower($search); // convert to lowercase

      // Create a transliterator instance
      $transliterator = Transliterator::createFromRules(':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: NFC;', Transliterator::FORWARD);

      // Transliterate Russian characters to Latin
      $search = $transliterator->transliterate($search);

      // Remove accents and special characters
      $search = preg_replace('/[^\w\s]/', '', $search);

      // Split search query into individual words
      $words = explode(' ', $search);

      // Search for each word in the database
      $items->where(function ($query) use ($words) {
          foreach ($words as $word) {
              $query->orWhere('title', 'LIKE', '%' . $word . '%')
                    ->orWhere('slug', 'LIKE', '%' . $word . '%');
          }
      });
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

