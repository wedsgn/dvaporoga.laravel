<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Transliterator;

class CarModel extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
      'title',
      'slug',
      'image',
      'image_mob',
      'description',
      'car_make_id',
      'meta_title',
      'meta_description',
      'meta_keywords',
      'og_url',
      'og_title',
      'og_description'
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
  public function getGenerationsCount() {
    return $this->cars()->count();
  }
  public function getFirstYear() {
    return substr($this->cars()->min('years'), 0, 4);
  }
  public function getLastYear() {
    return substr($this->cars()->max('years'), -4);
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
