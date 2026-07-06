<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Car\StoreRequest;
use App\Http\Requests\Admin\Car\UpdateRequest;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\Product;
use App\Support\UploadValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CarController extends BaseController
{
    public function index()
    {
        $user = Auth::user();
        $cars = Car::orderBy('title')->paginate(50);

        return view('admin.cars.index', compact('cars', 'user'));
    }

    public function show($car_slug)
    {
        $user = Auth::user();
        $item = Car::with([
            'car_model.car_make',
            'products' => fn($query) => $query->orderBy('title'),
        ])->whereSlug($car_slug)->firstOrFail();

        $relatedProducts = $item->products->map(function (Product $product) {
            return [
                'product' => $product,
                'image' => $this->resolveProductImagePreview($product),
                'has_custom_image' => filled($product->pivot?->image),
            ];
        });

        return view('admin.cars.show', compact('item', 'user', 'relatedProducts'));
    }

    public function updateProductImages(Request $request, $car_slug)
    {
        $car = Car::with('products')->whereSlug($car_slug)->firstOrFail();

        if ($car->products->isEmpty()) {
            return back()->withErrors([
                'product_images' => 'У этого автомобиля нет связанных деталей для обновления изображений.',
            ]);
        }

        $request->validate([
            'product_images' => ['nullable', 'array'],
            'product_images.*' => UploadValidation::fileImageRules('nullable'),
        ], UploadValidation::messages(['product_images.*'], 'image', false));

        $relatedProducts = $car->products->keyBy('id');
        $uploadedProducts = $request->file('product_images', []);
        $updatedCount = 0;

        if (empty($uploadedProducts)) {
            return back()->withErrors([
                'product_images' => 'Выберите хотя бы одну новую картинку у карточек деталей перед сохранением.',
            ]);
        }

        foreach ($uploadedProducts as $productId => $file) {
            $productId = (int) $productId;

            if (!$file || !$relatedProducts->has($productId)) {
                continue;
            }

            $this->storeProductImageForCar($car, $relatedProducts->get($productId), $file);
            $updatedCount++;
        }

        return back()->with('success', "Изображения обновлены. Изменено позиций: {$updatedCount}.");
    }

    public function create()
    {
        $user = Auth::user();
        $car_models = CarModel::all();

        return view('admin.cars.create', compact('user', 'car_models'));
    }

    public function store(StoreRequest $request)
    {
        $tagInputs = $request->input('tags', []);
        $offersInput = $request->input('offers', []);

        $data = $request->validated();
        $data = $this->format_data_service->changeTitleToId($data, CarModel::class, 'car_model_id');
        $data['slug'] = Str::slug($data['title']);

        foreach (['image', 'image_mob'] as $image) {
            if ($request->hasFile($image)) {
                $data[$image] = $this->upload_service->imageConvertAndStore(
                    $request,
                    $data[$image] ?? null,
                    $data['slug']
                );
            }
        }

        DB::transaction(function () use ($data, $tagInputs, $offersInput) {
            $car = Car::create($data);
            $this->replaceCarTags($car, $tagInputs);
            $this->replaceCarOffers($car, $offersInput);
        });

        return redirect()->route('admin.cars.index')->with('status', 'item-created');
    }

    public function edit($car_slug)
    {
        $user = Auth::user();
        $item = Car::with(['tags', 'offers'])->whereSlug($car_slug)->firstOrFail();
        $car_models = CarModel::all();

        return view('admin.cars.edit', compact('user', 'item', 'car_models'));
    }

    public function update(UpdateRequest $request, $car_slug)
    {
        $car = Car::whereSlug($car_slug)->firstOrFail();

        $tagInputs = $request->input('tags', []);
        $offersInput = $request->input('offers', []);

        $data = $request->validated();
        $data = $this->format_data_service->changeTitleToId($data, CarModel::class, 'car_model_id');
        $data['slug'] = Str::slug($data['title']);

        foreach (['image', 'image_mob'] as $image) {
            if ($request->hasFile($image)) {
                $data[$image] = $this->upload_service->imageConvertAndStore(
                    $request,
                    $data[$image] ?? null,
                    $data['slug']
                );
            }
        }

        DB::transaction(function () use ($car, $data, $tagInputs, $offersInput) {
            $car->update($data);
            $this->replaceCarTags($car, $tagInputs);
            $this->replaceCarOffers($car, $offersInput);
        });

        return redirect()->route('admin.cars.index')->with('status', 'item-updated');
    }

    public function destroy($car_slug)
    {
        $car = Car::whereSlug($car_slug)->firstOrFail();
        $car->delete();

        return redirect()->route('admin.cars.index')->with('status', 'item-deleted');
    }

    public function search(Request $request)
    {
        $user = Auth::user();
        $q = trim((string) $request->get('search', ''));

        $cars = Car::query()
            ->with(['car_model'])
            ->smartFilter($q)
            ->when($q === '', fn($query) => $query->orderBy('title'))
            ->when($q !== '', fn($query) => $query->orderByDesc('id'))
            ->paginate(50)
            ->appends(['search' => $q]);

        return view('admin.cars.index', compact('cars', 'user'));
    }

    private function resolveProductImagePreview(Product $product): array
    {
        $pivotPath = filled($product->pivot?->image) ? ltrim((string) $product->pivot->image, '/') : null;
        $adminPath = filled($product->image) ? ltrim((string) $product->image, '/') : null;
        $fallbackPath = $this->findDefaultProductImage($product->slug);

        $resolvedPath = $pivotPath ?: ($adminPath ?: $fallbackPath);

        return [
            'url' => $this->makeStoragePreviewUrl($resolvedPath),
            'path' => $resolvedPath,
            'source_label' => $pivotPath
                ? 'Индивидуально для этого авто'
                : ($adminPath
                    ? 'Основная картинка детали'
                    : ($fallbackPath ? 'Дефолт по slug' : 'Изображение не найдено')),
            'source_badge' => $pivotPath
                ? 'success'
                : ($adminPath ? 'primary' : ($fallbackPath ? 'warning' : 'danger')),
            'current_source_details' => [
                [
                    'label' => 'Для этого авто',
                    'value' => $pivotPath ?: 'не задана',
                    'icon' => 'ri-image-line',
                ],
                [
                    'label' => 'Основная у детали',
                    'value' => $adminPath ?: 'не задана',
                    'icon' => 'ri-folder-image-line',
                ],
                [
                    'label' => 'Резервная по slug',
                    'value' => $fallbackPath ?: 'не найдена',
                    'icon' => 'ri-gallery-line',
                ],
            ],
        ];
    }

    private function storeProductImageForCar(Car $car, Product $product, $file): void
    {
        $ext = $file->getClientOriginalExtension() ?: 'jpg';
        $path = $file->storeAs(
            "products/{$car->id}",
            "{$product->id}.{$ext}",
            'public'
        );

        $product->cars()->updateExistingPivot($car->id, [
            'image' => $path,
            'updated_at' => now(),
        ]);
    }

    private function findDefaultProductImage(?string $slug): ?string
    {
        if (blank($slug)) {
            return null;
        }

        foreach (['webp', 'jpg', 'jpeg', 'png'] as $ext) {
            $path = "products_default/{$slug}.{$ext}";

            if (Storage::disk('public')->exists($path)) {
                return $path;
            }
        }

        return null;
    }

    private function makeStoragePreviewUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (preg_match('~^https?://~i', $path)) {
            return $path;
        }

        return asset('storage/' . ltrim($path, '/'));
    }

    private function replaceCarOffers(Car $car, array $offersInput): void
    {
        $rows = collect($offersInput)->map(function ($r) {
            return [
                'title' => trim((string) ($r['title'] ?? '')),
                'price_from' => ($r['price_from'] ?? '') !== '' ? (int) $r['price_from'] : null,
                'price_old' => ($r['price_old'] ?? '') !== '' ? (int) $r['price_old'] : null,
                'currency' => trim((string) ($r['currency'] ?? '₽')) ?: '₽',
                'sort' => ($r['sort'] ?? '') !== '' ? (int) $r['sort'] : 1000,
                'is_active' => isset($r['is_active']) ? (bool) $r['is_active'] : true,
            ];
        })
            ->filter(fn($r) => $r['title'] !== '' || $r['price_from'] !== null || $r['price_old'] !== null)
            ->values();

        $car->offers()->delete();

        if ($rows->isNotEmpty()) {
            $car->offers()->createMany($rows->all());
        }
    }

    private function replaceCarTags(Car $car, array $tagInputs): void
    {
        $rows = collect($tagInputs)->map(function ($t) {
            if (is_array($t)) {
                return [
                    'title' => trim((string) ($t['title'] ?? '')),
                    'sort' => isset($t['sort']) && $t['sort'] !== '' ? (int) $t['sort'] : 1000,
                ];
            }

            return [
                'title' => trim((string) $t),
                'sort' => 1000,
            ];
        })
            ->filter(fn($r) => $r['title'] !== '')
            ->values();

        $car->tags()->delete();

        if ($rows->isEmpty()) {
            return;
        }

        $car->tags()->createMany($rows->all());
    }
}
