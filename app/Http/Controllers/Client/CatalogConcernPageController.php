<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\MainInfo;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogConcernPageController extends Controller
{
  public function index()
  {
    $products = Product::latest()->get();
    $car_makes = CarMake::visible()->orderBy('title')->get();
    $page = Page::whereSlug('katalog')->firstOrFail();

    return view('catalog_concern', compact('car_makes', 'products', 'page'));
  }

public function car_make_show($slug)
{
    $car_make = CarMake::where('slug', $slug)->firstOrFail();

    $car_models = $car_make->car_models()
        ->orderBy('title')
        ->get();

    $car_make_id = $car_make->id;
    $car_make_title = $car_make->title;
    $page = $car_make;

    return view('catalog_models', compact(
        'car_make',
        'car_models',
        'car_make_id',
        'car_make_title',
        'page'
    ));
}
  public function search(Request $request)
  {
    $search = $request->input('search');
    $car_makes = CarMake::visible()->smartFilter($search)->get();
    return view('partials.concern-card', compact('car_makes'));
  }
}
