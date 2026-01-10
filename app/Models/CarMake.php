<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Transliterator;

class CarMake extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'title',
    'slug',
    'image',
    'image_mob',
    'description',
    'meta_title',
    'meta_description',
    'meta_keywords',
    'og_url',
    'og_title',
    'og_description',
    'is_hidden',
    'norm_key',
    'last_import_run_id'
  ];
  public static $car_makes_routes = [
    'admin.car_makes.index',
    'admin.car_makes.search',
    'admin.car_makes.show',
    'admin.car_makes.edit',
    'admin.car_makes.create'
  ];

  protected $casts = [
    'is_hidden' => 'bool',
  ];

  public function scopeVisible($q)
  {
    return $q->where('is_hidden', false);
  }

  public function getRouteKeyName()
  {
    return 'slug';
  }

  public function orders()
  {
    return $this->belongsToMany(Order::class);
  }

  public function car_models()
  {
    return $this->hasMany(CarModel::class);
  }

  public function scopeSmartFilter($query, ?string $search)
  {
    $search = trim((string)$search);
    if ($search === '') return $query;

    $norm = mb_strtolower($search);
    $norm = str_replace(["–", "—", "-"], "-", $norm);
    $norm = preg_replace('/\s+/', ' ', $norm);

    $latin = $norm;
    try {
      $tr = Transliterator::createFromRules(
        ':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: NFC;',
        Transliterator::FORWARD
      );
      if ($tr) {
        $latin = mb_strtolower($tr->transliterate($norm));
      }
    } catch (\Throwable $e) {
    }

    $tokensNorm  = preg_split('/\s+/u', $norm, -1, PREG_SPLIT_NO_EMPTY);
    $tokensLatin = preg_split('/\s+/u', $latin, -1, PREG_SPLIT_NO_EMPTY);

    $tokens = array_values(array_unique(array_merge($tokensNorm ?: [], $tokensLatin ?: [])));
    $tokens = array_values(array_filter($tokens, fn($t) => $t !== ''));

    $query->select('*')->selectRaw("
        CASE
            WHEN LOWER(title) = ? THEN 300
            WHEN LOWER(title) LIKE ? THEN 220
            WHEN LOWER(title) = ? THEN 290
            WHEN LOWER(title) LIKE ? THEN 210

            WHEN LOWER(slug) = ? THEN 180
            WHEN LOWER(slug) LIKE ? THEN 140
            WHEN LOWER(slug) = ? THEN 170
            WHEN LOWER(slug) LIKE ? THEN 130
            ELSE 0
        END AS relevance
    ", [
      $norm,
      $norm . '%',
      $latin,
      $latin . '%',
      $norm,
      $norm . '%',
      $latin,
      $latin . '%',
    ]);


    $query->where(function ($q) use ($norm, $latin) {
      foreach (array_unique([$norm, $latin]) as $needle) {
        if (!$needle) continue;

        $q->orWhereRaw('LOWER(title) LIKE ?', ['%' . $needle . '%'])
          ->orWhereRaw('LOWER(slug) LIKE ?',  ['%' . mb_strtolower(Str::slug($needle)) . '%']);
      }
    });

    $query->orWhere(function ($q) use ($tokens) {
      foreach ($tokens as $t) {
        $t = mb_strtolower(trim($t));
        if ($t === '') continue;
        if (mb_strlen($t) < 2) continue;

        $q->where(function ($qq) use ($t) {
          $qq->whereRaw('LOWER(title) LIKE ?', ['%' . $t . '%'])
            ->orWhereRaw('LOWER(slug) LIKE ?',  ['%' . $t . '%']);
        });
      }
    });

    return $query
      ->orderByDesc('relevance')
      ->orderByDesc('id');
  }


  public function getImageUrlAttribute(): ?string
  {
    $img = $this->image ?? null;
    if (!$img) return null;

    if (preg_match('~^https?://~i', $img)) return $img;

    if ($img === 'default') return $img;

    return asset('storage/' . ltrim($img, '/'));
  }
}
