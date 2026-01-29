<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Product\StoreRequest;
use App\Http\Requests\Admin\Product\UpdateRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Car;

class ProductController extends BaseController
{
  public function index()
  {
    $user = Auth::user();

    // car больше не belongsTo, теперь many-to-many
    $products = Product::query()
      ->withCount('cars')
      ->orderBy('id', 'DESC')
      ->paginate(50);

    return view('admin.products.index', compact('products', 'user'));
  }

  public function show($product_slug)
  {
    $user = Auth::user();

    $item = Product::query()
      ->withCount('cars')
      ->whereSlug($product_slug)
      ->firstOrFail();

    return view('admin.products.show', compact('item', 'user'));
  }

  public function create(Request $request)
  {
    $user = Auth::user();

    $q = trim((string)$request->get('q', ''));

    $carsQuery = \App\Models\Car::query()->orderBy('title');
    if ($q !== '') {
      $carsQuery->where('title', 'ILIKE', "%{$q}%"); // Postgres
    }

    $cars = $carsQuery->paginate(50)->withQueryString();

    // для create: начальные выбранные — пустые (или old(), но мы используем localStorage)
    $selectedCarIds = [];

    return view('admin.products.create', compact('user', 'cars', 'q', 'selectedCarIds'));
  }

  public function store(StoreRequest $request)
  {
    $data = $request->validated();

    // ТОВАР БАЗОВЫЙ: car_id всегда null
    if (array_key_exists('car_id', $data)) {
      unset($data['car_id']);
    }

    // slug: просто от title, но уникальный
    $baseSlug = Str::slug($data['title'] ?? '');
    $data['slug'] = $this->makeUniqueProductSlug($baseSlug);

    // дефолтные картинки товара (не индивидуальные)
    foreach (['image', 'image_mob'] as $image) {
      if ($request->hasFile($image)) {
        $data[$image] = $this->upload_service
          ->imageConvertAndStore($request, $data[$image], $data['slug']);
      } else {
        // если в форме этих полей уже нет — ничего
        unset($data[$image]);
      }
    }

    $product = Product::create($data);

    $ids = [];
    $json = (string)$request->input('car_ids_json', '[]');
    $tmp = json_decode($json, true);
    if (is_array($tmp)) {
      $ids = array_values(array_unique(array_map('intval', $tmp)));
    }
    $product->cars()->sync($ids);
    return redirect()
      ->route('admin.products.edit', $product->slug)
      ->with('status', 'item-created');
  }

  public function edit(Request $request, $product_slug)
  {
    $user = Auth::user();

    $item = Product::query()
      ->withCount('cars')
      ->whereSlug($product_slug)
      ->firstOrFail();

    $q = trim((string)$request->get('q', ''));

    $carsQuery = \App\Models\Car::query()->orderBy('title');
    if ($q !== '') {
      $carsQuery->where('title', 'ILIKE', "%{$q}%");
    }

    $cars = $carsQuery->paginate(50)->withQueryString();

    $selectedCarIds = $item->cars()
      ->pluck('cars.id')
      ->map(fn($v) => (int) $v)
      ->all();

    sort($selectedCarIds);

    $serverSig = sha1(json_encode($selectedCarIds));

    return view('admin.products.edit', compact(
      'user',
      'item',
      'cars',
      'q',
      'selectedCarIds',
      'serverSig'
    ));
  }
  public function update(UpdateRequest $request, $product_slug)
  {
    $product = Product::whereSlug($product_slug)->firstOrFail();

    $data = $request->validated();

    // car_id больше не используется
    unset($data['car_id']);

    // slug не меняем
    unset($data['slug']);

    // дефолтные картинки товара (можно оставить, или вообще запретить)
    foreach (['image', 'image_mob'] as $image) {
      if ($request->hasFile($image)) {
        $data[$image] = $this->upload_service
          ->imageConvertAndStore($request, $data[$image], $product->slug);
      } else {
        unset($data[$image]);
      }
    }

    $product->update($data);
    $ids = [];
    $json = (string)$request->input('car_ids_json', '[]');
    $tmp = json_decode($json, true);
    if (is_array($tmp)) {
      $ids = array_values(array_unique(array_map('intval', $tmp)));
    }
    $product->cars()->sync($ids);
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

    $q = Product::query()->withCount('cars')->orderBy('id', 'DESC');

    if ($search !== '') {
      $q->smartFilter($search);
    }

    $products = $q->paginate(50);

    return view('admin.products.index', compact('products', 'user'));
  }

  private function makeUniqueProductSlug(string $baseSlug): string
  {
    $slug = $baseSlug !== '' ? $baseSlug : 'product';
    $n = 2;

    while (Product::query()->where('slug', $slug)->exists()) {
      $slug = $baseSlug . '-' . $n;
      $n++;
      if ($n > 2000) {
        $slug = $baseSlug . '-' . Str::random(6);
        break;
      }
    }

    return $slug;
  }
}
