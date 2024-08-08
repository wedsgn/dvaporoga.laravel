<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CarMake;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogProductPageController extends Controller
{
  public function car_generation_show($car_make_slug, $model_slug, $slug)
  {
    $car_make = CarMake::where('slug', $car_make_slug)->firstOrFail();
    $car_model = $car_make->car_models()->where('slug', $model_slug)->firstOrFail();
    $cars = $car_model->cars()->where('slug', $slug)->firstOrFail();

    $products = $cars->products;
    return view('catalog_products', compact('products'));
  }
}

