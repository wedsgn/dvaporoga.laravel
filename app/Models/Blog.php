<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Transliterator;

class Blog extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
      'title',
      'slug',
      'image',
      'image_mob',
      'description_short',
      'description',
      'meta_title',
      'meta_description',
      'meta_keywords',
      'og_url',
      'og_title',
      'og_description'
  ];
  public static $blogs_routes = [
    'admin.blogs.index',
    'admin.blogs.search',
    'admin.blogs.show',
    'admin.blogs.edit',
    'admin.blogs.create'
  ];
  public function getRouteKeyName()
  {
      return 'slug';
  }

  public function scopeFilter($items, $search)
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
                    ->orWhere('slug', 'LIKE', '%' . $word . '%')
                    ->orWhere('description', 'LIKE', '%' . $word . '%');
          }
      });
  }
  return $items;
  }
}
