<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\CarMake;
use App\Models\Product;
use Illuminate\Http\Request;

class BlogPageController extends Controller
{
  public function index()
  {
    $products = Product::latest()->limit(8)->get();
    $blogs = Blog::latest()->paginate(10);
    return view('blog', compact('blogs', 'products'));
  }

  public function show($slug)
  {
    $blog = Blog::where('slug', $slug)->first();
    if (!$blog) {
      abort(404);
    }
    return view('blog-single', compact('blog'));
  }

  public function search(Request $request)
  {
    $search = $request->input('search');
    $blogs = Blog::filter($search)->paginate(10);
    return view('blog', compact('blogs'));
  }
}

