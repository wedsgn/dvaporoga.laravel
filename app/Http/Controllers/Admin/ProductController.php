<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreRequest;
use App\Http\Requests\Admin\Product\UpdateRequest;
use App\Models\Car;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class ProductController extends BaseController
{
  public function index()
  {
    $user = Auth::user();
    $products = Product::orderBy('id', 'DESC')->paginate(50);
    return view('admin.products.index', compact('products', 'user'));
  }

  public function show($product_slug)
  {
    $user = Auth::user();
    $item = Product::whereSlug($product_slug)->firstOrFail();

    return view('admin.products.show', compact('item', 'user'));
  }

  public function create()
  {
    $user = Auth::user();
    $cars = Car::all();

    return view('admin.products.create', compact('user', 'cars'));
  }
  public function store(StoreRequest $request)
  {
    $data = $request->validated();
    $data['slug'] = Str::slug($data['title']);
    $split_data = $this->format_data_service->cutArraysFromRequest(
      $data,
      [
        'cars'
      ]
    );
    $data = $split_data['data'];
    foreach (['image', 'image_mob'] as $image) {
      if ($request->hasFile($image)) {
        $data[$image] = $this->upload_service->imageConvertAndStore($request, $data[$image], $data['slug']);
      }
    }
    $product = Product::firstOrCreate($data);
    $this->format_data_service->writeDataToTable($product, $split_data['arreyIds']);

    return redirect()->route('admin.products.index')->with('status', 'item-created');
  }
  public function edit($product_slug)
  {
    $user = Auth::user();
    $item = Product::whereSlug($product_slug)->firstOrFail();
    $cars = Car::all();

    return view('admin.products.edit', compact('user', 'item', 'cars'));
  }
  public function update(UpdateRequest $request, $product_slug)
  {
    $product = Product::whereSlug($product_slug)->firstOrFail();
    $data = $request->validated();
    $data['slug'] = Str::slug($data['title']);
    $split_data = $this->format_data_service->cutArraysFromRequest(
      $data,
      [
        'cars'
      ]
    );
    $data = $split_data['data'];

    foreach (['image', 'image_mob'] as $image) {
      if ($request->hasFile($image)) {
        $data[$image] = $this->upload_service->imageConvertAndStore($request, $data[$image], $data['slug']);
      }
    }

    $product->update($data);
    $this->format_data_service->writeDataToTable($product, $split_data['arreyIds']);

    return redirect()->route('admin.products.index')->with('status', 'item-updated');
  }

  public function destroy($product_slug)
  {
    $product = Product::whereSlug($product_slug)->firstOrFail();
    // $product->delete_files($product);
    $product->delete();
    return redirect()->route('admin.products.index')->with('status', 'item-deleted');
  }

  public function search(Request $request)
  {
    $user = Auth::user();
    if (request('search') == null) :
      $products = Product::orderBy('id', 'DESC')->paginate(50);
    else :
      $products = Product::filter()->paginate(50);
    endif;
    return view('admin.products.index', compact('products', 'user'));
  }
}
