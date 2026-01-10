<?php

namespace App\Http\Controllers\Admin;

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

        $products = Product::with('car')
            ->orderBy('id', 'DESC')
            ->paginate(50);

        return view('admin.products.index', compact('products', 'user'));
    }

    public function show($product_slug)
    {
        $user = Auth::user();

        $item = Product::with('car')
            ->whereSlug($product_slug)
            ->firstOrFail();

        return view('admin.products.show', compact('item', 'user'));
    }

    public function create()
    {
        $user = Auth::user();

        // выбираем одну машину (car_id)
        $cars = Car::orderBy('title')->get();

        return view('admin.products.create', compact('user', 'cars'));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        // slug должен быть уникальным: title + car_id
        // пример: "Порог" + car_id=123 -> "porog-123"
        $baseSlug = Str::slug($data['title']);
        $data['slug'] = $baseSlug . '-' . $data['car_id'];

        foreach (['image', 'image_mob'] as $image) {
            if ($request->hasFile($image)) {
                $data[$image] = $this->upload_service
                    ->imageConvertAndStore($request, $data[$image], $data['slug']);
            }
        }

        Product::create($data);

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'item-created');
    }

    public function edit($product_slug)
    {
        $user = Auth::user();

        $item = Product::with('car')
            ->whereSlug($product_slug)
            ->firstOrFail();

        $cars = Car::orderBy('title')->get();

        return view('admin.products.edit', compact('user', 'item', 'cars'));
    }

    public function update(UpdateRequest $request, $product_slug)
    {
        $product = Product::whereSlug($product_slug)->firstOrFail();

        $data = $request->validated();

        // ВАЖНО: slug не пересчитываем на update, иначе сломаешь URL
        unset($data['slug']);

        foreach (['image', 'image_mob'] as $image) {
            if ($request->hasFile($image)) {
                // для загрузки используем текущий slug продукта (он стабильный)
                $data[$image] = $this->upload_service
                    ->imageConvertAndStore($request, $data[$image], $product->slug);
            }
        }

        $product->update($data);

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'item-updated');
    }

    public function destroy($product_slug)
    {
        $product = Product::whereSlug($product_slug)->firstOrFail();
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'item-deleted');
    }

public function search(Request $request)
{
    $user = Auth::user();
    $search = trim((string) $request->input('search'));

    if ($search === '') {
        $products = Product::with('car')
            ->orderBy('id', 'DESC')
            ->paginate(50);
    } else {
        $products = Product::with('car')
            ->smartFilter($search)
            ->paginate(50);
    }

    return view('admin.products.index', compact('products', 'user'));
}
}
