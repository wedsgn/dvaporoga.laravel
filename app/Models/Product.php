<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Transliterator;

class Product extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
      'title',
      'slug',
      'image',
      'image_mob',
      'description',
      'sort',
      'meta_title',
      'meta_description',
      'meta_keywords',
      'og_url',
      'og_title',
      'og_description'
  ];
  public static $products_routes = [
    'admin.products.index',
    'admin.products.search',
    'admin.products.show',
    'admin.products.edit',
    'admin.products.create'
  ];
  public function getRouteKeyName()
  {
      return 'slug';
  }
  public function cars()
  {
      return $this->belongsToMany(Car::class);
  }
  public function car_make()
  {
      return $this->belongsTo(CarMake::class);
  }
  public function sizes()
  {
      return $this->belongsToMany(Size::class);
  }
  public function prices()
  {
      return $this->belongsToMany(Price::class, 'product_price');
  }
  public function thicknesses()
  {
      return $this->belongsToMany(Thickness::class, 'product_thickness');
  }
  public function steel_types()
  {
      return $this->belongsToMany(SteelType::class);
  }
  public function types()
  {
      return $this->belongsToMany(Type::class);
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
