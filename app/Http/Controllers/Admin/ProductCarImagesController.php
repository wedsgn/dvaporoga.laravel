<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductCarImagesController extends Controller
{
  public function index(Request $request, Product $product)
  {
    $user = Auth::user();
    $q = trim((string)$request->get('q', ''));

    $carsQuery = Car::query()
      ->select('cars.*')
      ->join('car_product', 'car_product.car_id', '=', 'cars.id')
      ->where('car_product.product_id', (int)$product->id);

    if ($q !== '') {
      $carsQuery->where('cars.title', 'ILIKE', '%' . $q . '%'); // Postgres
    }

    $cars = $carsQuery
      ->orderBy('cars.title')
      ->paginate(30)
      ->withQueryString();

    $pivotImages = DB::table('car_product')
      ->where('product_id', (int)$product->id)
      ->pluck('image', 'car_id'); // [car_id => image]

    return view('admin.products.cars-images', compact('user', 'product', 'cars', 'pivotImages', 'q'));
  }

  public function updateImage(Request $request, Product $product, Car $car)
  {
    $exists = DB::table('car_product')
      ->where('product_id', (int)$product->id)
      ->where('car_id', (int)$car->id)
      ->exists();

    if (!$exists) {
      return back()->withErrors(['image' => 'У этой машины нет связи с этим товаром.']);
    }

    $request->validate([
      'image' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
    ]);

    $file = $request->file('image');
    $ext  = $file->getClientOriginalExtension() ?: 'jpg';

    $path = $file->storeAs(
      "products/{$car->id}",
      "{$product->id}.{$ext}",
      'public'
    );

    DB::table('car_product')
      ->where('product_id', (int)$product->id)
      ->where('car_id', (int)$car->id)
      ->update([
        'image' => $path,
        'updated_at' => now(),
      ]);

    return back()->with('success', "Изображение обновлено для: {$car->title}");
  }

  public function destroy(Product $product, Car $car)
  {
    $pivot = $product->cars()->where('car_id', $car->id)->first()?->pivot;

    if (!$pivot || empty($pivot->image)) {
      return back()->with('success', 'Pivot-картинка отсутствует.');
    }

    $path = ltrim($pivot->image, '/');
    if (Storage::disk('public')->exists($path)) {
      Storage::disk('public')->delete($path);
    }

    // чистим поле в pivot
    $product->cars()->updateExistingPivot($car->id, [
      'image' => null,
    ]);

    return back()->with('success', 'Картинка для этой машины удалена.');
  }
}
