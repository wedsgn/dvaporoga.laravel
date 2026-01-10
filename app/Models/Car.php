<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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
    'og_description',
    'norm_key',
    'last_import_run_id'
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
    return $this->hasMany(Product::class);
  }

  public function car_model()
  {
    return $this->belongsTo(CarModel::class);
  }

  public function tags()
  {
    return $this->hasMany(CarTag::class)->orderBy('sort')->orderBy('id');
  }

  public function offers()
  {
    return $this->hasMany(CarOffer::class)->orderBy('sort')->orderBy('id');
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
      $tr = \Transliterator::createFromRules(
        ':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: NFC;',
        \Transliterator::FORWARD
      );
      if ($tr) $latin = mb_strtolower($tr->transliterate($norm));
    } catch (\Throwable $e) {
    }

    $tokensNorm  = preg_split('/\s+/u', $norm, -1, PREG_SPLIT_NO_EMPTY);
    $tokensLatin = preg_split('/\s+/u', $latin, -1, PREG_SPLIT_NO_EMPTY);

    $allTokens = array_values(array_unique(array_merge($tokensNorm ?: [], $tokensLatin ?: [])));
    $allTokens = array_values(array_filter($allTokens, fn($t) => $t !== ''));

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
        $needle = trim((string)$needle);
        if ($needle === '') continue;

        $q->orWhereRaw('LOWER(title) LIKE ?', ['%' . $needle . '%'])
          ->orWhereRaw('LOWER(generation) LIKE ?', ['%' . $needle . '%'])
          ->orWhereRaw('LOWER(years) LIKE ?', ['%' . $needle . '%'])
          ->orWhereRaw('LOWER(body) LIKE ?', ['%' . $needle . '%'])
          ->orWhereRaw('LOWER(artikul) LIKE ?', ['%' . $needle . '%'])
          ->orWhereRaw('LOWER(slug) LIKE ?', ['%' . mb_strtolower(\Illuminate\Support\Str::slug($needle)) . '%']);

        $q->orWhereHas('car_model', function ($qm) use ($needle) {
          $qm->whereRaw('LOWER(title) LIKE ?', ['%' . $needle . '%']);
        });
      }
    });

    $query->orWhere(function ($q) use ($allTokens) {
      foreach ($allTokens as $t) {
        $t = mb_strtolower(trim($t));
        if ($t === '') continue;
        if (mb_strlen($t) < 2) continue;

        $q->where(function ($qq) use ($t) {
          $qq->whereRaw('LOWER(title) LIKE ?', ['%' . $t . '%'])
            ->orWhereRaw('LOWER(slug) LIKE ?', ['%' . $t . '%'])
            ->orWhereRaw('LOWER(generation) LIKE ?', ['%' . $t . '%'])
            ->orWhereRaw('LOWER(body) LIKE ?', ['%' . $t . '%'])
            ->orWhereRaw('LOWER(years) LIKE ?', ['%' . $t . '%'])
            ->orWhereHas('car_model', function ($qm) use ($t) {
              $qm->whereRaw('LOWER(title) LIKE ?', ['%' . $t . '%']);
            });
        });
      }
    });

    return $query->orderByDesc('relevance')->orderByDesc('id');
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
