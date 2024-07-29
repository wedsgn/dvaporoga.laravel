<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CarMake;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogGenerationPageController extends Controller
{
  public function index()
  {
    $products = Product::latest()->limit(8)->get();
    $car_makes = CarMake::latest()->paginate(10);
    return view('catalog_concern', compact('car_makes', 'products'));
  }

  public function car_make_show($slug)
  {
    $car_make = CarMake::where('slug', $slug)->first();
    if (!$car_make) {
      abort(404);
    }
    return view('catalog_concern_single', compact('car_make'));
  }

  public function search(Request $request)
  {
    $search = $request->input('search');
    $car_makes = CarMake::filter($search)->paginate(10);
    return view('catalog_concern', compact('car_makes'));
  }
}

