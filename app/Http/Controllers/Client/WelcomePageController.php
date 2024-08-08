<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\CarMake;
use App\Models\Product;

class WelcomePageController extends Controller
{
  public function index()
  {
    $products = Product::latest()->get();
    $car_makes = CarMake::latest()->limit(12)->get();
    $blogs = Blog::latest()->limit(10)->get();
    return view('welcome', compact('products', 'car_makes', 'blogs'));
  }
}

