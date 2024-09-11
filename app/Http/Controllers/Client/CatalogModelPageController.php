<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CarMake;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogModelPageController extends Controller
{
  public function car_model_show($car_make_slug, $slug)
  {
    $car_make = CarMake::where('slug', $car_make_slug)->firstOrFail();

    $car_model = $car_make->car_models()->where('slug', $slug)->firstOrFail();
    $generations = $car_model->cars()->orderBy('years')->get()->groupBy(function ($item, $key) {
        return $item->generation;
    });
    $products = Product::latest()->get();
    return view('catalog_generations', compact('generations','car_make', 'car_model','products'));
  }

  /**
   * Searches for car models in a specific car make.
   *
   * @param Request $request The HTTP request object.
   * @param string $car_make_slug The slug of the car make.
   * @return \Illuminate\Contracts\View\View The view displaying the search results.
   * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the car make is not found.
   */
  public function search(Request $request, $car_make_slug)
  {
    $search = $request->input('search');
    $car_make = CarMake::where('slug', $car_make_slug)->firstOrFail();
    $car_models = $car_make->car_models()->filter($search)->get();
    return view('partials.model-card', compact('car_models', 'car_make'));
  }
}

