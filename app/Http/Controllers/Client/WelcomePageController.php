<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\CarMake;
use App\Models\Order;
use App\Models\Page;
use App\Models\Product;
use App\Models\Block;

class WelcomePageController extends Controller
{
  public function index()
  {
    $products = Product::orderBy('sort', 'asc')->take(6)->get();
    $order = Order::where('title', 'order_car_makes_home_page')->firstOrFail();
    $car_makes = $order->car_makes()->orderBy('car_make_order.id', 'asc')->limit(12)->get();
    $makesForForm = CarMake::visible()->orderBy('title', 'asc')->get(['id', 'title']);
    $blogs = Blog::latest()->limit(10)->get();

    $page = Page::where('slug', 'home')
      ->with(['banners' => function ($q) {
        $q->where('is_active', 1)->orderBy('sort_order');
      }])
      ->firstOrFail();

    $galleryBlock = Block::where('key', 'home_gallery')->first();
    $catalogPartsBlock = Block::where('key', 'catalog_default_parts')->first();
    $repairExamplesBlock = Block::where('key', 'repair_examples')->first();

    return view('welcome', compact(
      'products',
      'car_makes',
      'blogs',
      'page',
      'makesForForm',
      'galleryBlock',
      'catalogPartsBlock',
      'repairExamplesBlock',
    ));
  }
}
