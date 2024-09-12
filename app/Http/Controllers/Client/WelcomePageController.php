<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\CarMake;
use App\Models\Order;
use App\Models\Product;

class WelcomePageController extends Controller
{
  public function index()
  {
    $products = Product::orderBy('sort', 'asc')->take(6)->get();
    $order = Order::where('title', 'order_car_makes_home_page')->firstOrFail();
    $car_makes = $order->car_makes()->orderBy('car_make_order.id', 'asc')->limit(12)->get();
    $blogs = Blog::latest()->limit(10)->get();
    return view('welcome', compact('products', 'car_makes', 'blogs'));
  }
}
