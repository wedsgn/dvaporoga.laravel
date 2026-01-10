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
      'og_description',
      'norm_key',
      'last_import_run_id'
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
        if ($tr) $latin = mb_strtolower($tr->transliterate($norm));
    } catch (\Throwable $e) {}

    $tokensNorm  = preg_split('/\s+/u', $norm, -1, PREG_SPLIT_NO_EMPTY) ?: [];
    $tokensLatin = preg_split('/\s+/u', $latin, -1, PREG_SPLIT_NO_EMPTY) ?: [];

    $tokens = array_values(array_unique(array_merge($tokensNorm, $tokensLatin)));
    $tokens = array_values(array_filter($tokens, fn($t) => trim($t) !== '' && mb_strlen($t) >= 2));

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
        $norm,  $norm.'%',
        $latin, $latin.'%',
        $norm,  $norm.'%',
        $latin, $latin.'%',
    ]);

    $query->where(function ($q) use ($norm, $latin) {
        foreach (array_unique([$norm, $latin]) as $needle) {
            $needle = trim((string)$needle);
            if ($needle === '') continue;

            $q->orWhereRaw('LOWER(title) LIKE ?', ['%' . $needle . '%'])
              ->orWhereRaw('LOWER(slug)  LIKE ?', ['%' . mb_strtolower(Str::slug($needle)) . '%']);
        }
    });

    $query->orWhere(function ($q) use ($tokens) {
        foreach ($tokens as $t) {
            $t = mb_strtolower(trim($t));
            $q->where(function ($qq) use ($t) {
                $qq->whereRaw('LOWER(title) LIKE ?', ['%' . $t . '%'])
                   ->orWhereRaw('LOWER(slug)  LIKE ?', ['%' . $t . '%']);
            });
        }
    });

    $query->orWhereHas('car_make', function ($qm) use ($norm, $latin, $tokens) {
        foreach (array_unique([$norm, $latin]) as $needle) {
            $needle = trim((string)$needle);
            if ($needle === '') continue;
            $qm->orWhereRaw('LOWER(title) LIKE ?', ['%' . $needle . '%']);
        }

        foreach ($tokens as $t) {
            $t = mb_strtolower(trim($t));
            $qm->orWhereRaw('LOWER(title) LIKE ?', ['%' . $t . '%']);
        }
    });

    return $query->orderByDesc('relevance')->orderByDesc('id');
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

  public function getImageUrlAttribute(): ?string
{
    $img = $this->image ?? null;
    if (!$img) return null;

    if (preg_match('~^https?://~i', $img)) return $img;


    if ($img === 'default') return $img;

    return asset('storage/' . ltrim($img, '/'));
}
}

