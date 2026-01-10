<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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

    // Цены
    'price',
    'price_old',
    'discount_percentage',

    // SEO
    'meta_title',
    'meta_description',
    'meta_keywords',
    'og_url',
    'og_title',
    'og_description',

    'norm_key',
    'car_id',
    'last_import_run_id'
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

  public function car()
  {
    return $this->belongsTo(Car::class);
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
    } catch (\Throwable $e) {
    }

    $tokensNorm  = preg_split('/\s+/u', $norm, -1, PREG_SPLIT_NO_EMPTY) ?: [];
    $tokensLatin = preg_split('/\s+/u', $latin, -1, PREG_SPLIT_NO_EMPTY) ?: [];
    $tokens = array_values(array_unique(array_merge($tokensNorm, $tokensLatin)));
    $tokens = array_values(array_filter($tokens, fn($t) => mb_strlen(trim($t)) >= 2));

    // JOIN вместо orWhereHas
    $query->leftJoin('cars', 'cars.id', '=', 'products.car_id')
      ->select('products.*');

    // Ранжирование оставляем (оно почти не влияет после индексов pg_trgm)
    $query->selectRaw("
        CASE
            WHEN LOWER(products.title) = ? THEN 300
            WHEN LOWER(products.title) LIKE ? THEN 220
            WHEN LOWER(products.slug)  = ? THEN 180
            WHEN LOWER(products.slug)  LIKE ? THEN 140
            ELSE 0
        END AS relevance
    ", [
      $norm,
      $norm . '%',
      Str::slug($norm),
      Str::slug($norm) . '%',
    ]);

    $query->where(function ($q) use ($norm, $latin, $tokens) {
      // фразовый OR
      foreach (array_unique([$norm, $latin]) as $needle) {
        $needle = trim($needle);
        if ($needle === '') continue;

        $slugNeedle = mb_strtolower(Str::slug($needle));

        $q->orWhereRaw('LOWER(products.title) LIKE ?', ['%' . $needle . '%'])
          ->orWhereRaw('LOWER(products.slug)  LIKE ?', ['%' . $slugNeedle . '%'])
          ->orWhereRaw('LOWER(cars.title)     LIKE ?', ['%' . $needle . '%']);
      }

      // токены AND (внутри одной ветки)
      if ($tokens) {
        $q->orWhere(function ($qq) use ($tokens) {
          foreach ($tokens as $t) {
            $t = mb_strtolower(trim($t));
            $qq->where(function ($tq) use ($t) {
              $tq->whereRaw('LOWER(products.title) LIKE ?', ['%' . $t . '%'])
                ->orWhereRaw('LOWER(products.slug)  LIKE ?', ['%' . $t . '%'])
                ->orWhereRaw('LOWER(cars.title)     LIKE ?', ['%' . $t . '%']);
            });
          }
        });
      }
    });

    return $query->orderByDesc('relevance')->orderByDesc('products.id');
  }
}
