<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RequestProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestProductController extends Controller
{
  public function index()
  {
      $user = Auth::user();
      $request_products = RequestProduct::orderBy('id', 'DESC')->paginate(50);
      return view('admin.request_products.index', compact('request_products', 'user'));
  }

  public function show($id)
  {
      $item = RequestProduct::findOrFail($id);
      $user = Auth::user();
      $data = json_decode($item->data, true);
      $products = [];
      if(isset($data)) {
          foreach ($data as $id) {
              $product = \App\Models\Product::find($id);
              if ($product) {
                  $products[] = $product;
              }
          }
      }
      else {
          $products = [];
      }
      return view('admin.request_products.show', compact('item', 'user', 'products'));
  }
  public function search(Request $request)
  {
      $user = Auth::user();
      if (request('search') == null) :
          $request_products = RequestProduct::orderBy('id', 'DESC')->paginate(50);
      else :
          $request_products = RequestProduct::filter()->paginate(50);
      endif;
      return view('admin.request_products.index', compact('request_products', 'user'));
  }
}

