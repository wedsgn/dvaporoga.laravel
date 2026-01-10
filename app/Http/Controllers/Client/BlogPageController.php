<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\CarMake;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Http\Request;

class BlogPageController extends Controller
{
  public function index(Request $request)
  {
    $products = Product::latest()->limit(8)->get();
    $blogs = Blog::latest()->paginate(8, ['*'], 'page', $request->page);
    $pageCount = $blogs->lastPage();
    $currentPage = $blogs->currentPage();
    $page = Page::whereSlug('blog')->firstOrFail();

    return view('blog', compact('blogs', 'products', 'pageCount', 'currentPage', 'page'));
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
    $blogs = Blog::smartFilter($search)->paginate(8);
    $pageCount = $blogs->lastPage();
    $currentPage = $blogs->currentPage();
    return view('partials.blog-card', compact('blogs', 'pageCount', 'currentPage'));
  }

  public function add_more(Request $request)
  {
    $blogs = Blog::latest()->paginate(8, ['*'], 'page', $request->page);
    $pageCount = $blogs->lastPage();
    $currentPage = $blogs->currentPage();
    return view('partials.blog-card', compact('blogs', 'pageCount', 'currentPage'));
  }
}

