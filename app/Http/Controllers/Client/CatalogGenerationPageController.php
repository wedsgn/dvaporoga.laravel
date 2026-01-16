<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CarMake;

class CatalogGenerationPageController extends Controller
{
  public function car_generation_show($car_make_slug, $model_slug, $slug)
  {
    $car_make = CarMake::where('slug', $car_make_slug)->firstOrFail();
    $car_model = $car_make->car_models()->where('slug', $model_slug)->firstOrFail();
    $car = $car_model->cars()->where('slug', $slug)->firstOrFail();

    $page = $car;

$products = $car->products()
    ->select('products.*')
    ->withPivot(['image', 'image_mob'])
    ->orderByRaw("
        CASE
            -- 1) Пороги
            WHEN products.title ILIKE 'порог%' THEN 0
            WHEN products.title ILIKE 'усилитель%порог%' THEN 1

            -- 2) Арки (все)
            WHEN products.title ILIKE 'арка%' THEN 10

            -- 3) Всё остальное
            ELSE 100
        END
    ")
    ->orderBy('products.sort')
    ->orderBy('products.id')
    ->get();

    return view('catalog_product', compact('products', 'car', 'car_model', 'car_make', 'page'));
  }
}
