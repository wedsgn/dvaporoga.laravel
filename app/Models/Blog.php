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

  public function scopeFilter($items)
  {
      if (request('search') !== null) {
          $items->where('id', 'ilike', '%' . request('search') . '%')
          ->orWhere('slug', 'ilike', '%' . request('search') . '%')
          ->orWhere('title', 'ilike', '%' . request('search') . '%');
      }
      return $items;
  }
}
