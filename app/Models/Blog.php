<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
      'title',
      'slug',
      'image',
      'image_mob',
      'description_short',
      'description'
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
          $items->where('title', 'ilike', "%{$search}%")
          ->orWhere('description', 'ilike', "%{$search}%")
          ->paginate(10);
      }
      return $items;
  }
}
