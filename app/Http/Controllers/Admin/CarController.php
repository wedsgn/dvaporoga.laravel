<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Car\StoreRequest;
use App\Http\Requests\Admin\Car\UpdateRequest;
use App\Models\Car;
use App\Models\CarModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CarController extends BaseController
{
    public function index()
    {
        $user = Auth::user();
        $cars = Car::orderBy('id', 'DESC')->paginate(50);
        return view('admin.cars.index', compact('cars', 'user'));
    }

    public function show($car_slug)
    {
        $user = Auth::user();
        $item = Car::whereSlug($car_slug)->firstOrFail();

        return view('admin.cars.show', compact('item', 'user'));
    }

    public function create()
    {
        $user = Auth::user();
        $car_models = CarModel::all();

        return view('admin.cars.create', compact('user', 'car_models'));
    }

    public function store(StoreRequest $request)
    {
        $tagInputs   = $request->input('tags', []);
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

        // СОРТИРОВКА подтянется из relation (orderBy sort)
        $item = Car::with(['tags', 'offers'])->whereSlug($car_slug)->firstOrFail();

        $car_models = CarModel::all();

        return view('admin.cars.edit', compact('user', 'item', 'car_models'));
    }

    public function update(UpdateRequest $request, $car_slug)
    {
        $car = Car::whereSlug($car_slug)->firstOrFail();

        $tagInputs   = $request->input('tags', []);
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
        $q = trim((string)$request->get('search', ''));

        $cars = Car::query()
            ->with(['car_model'])
            ->smartFilter($q)
            ->orderByDesc('id')
            ->paginate(50)
            ->appends(['search' => $q]);

        return view('admin.cars.index', compact('cars', 'user'));
    }

    private function replaceCarOffers(Car $car, array $offersInput): void
    {
        $rows = collect($offersInput)->map(function ($r) {
            return [
                'title'      => trim((string)($r['title'] ?? '')),
                'price_from' => ($r['price_from'] ?? '') !== '' ? (int)$r['price_from'] : null,
                'price_old'  => ($r['price_old'] ?? '') !== '' ? (int)$r['price_old'] : null,
                'currency'   => trim((string)($r['currency'] ?? '₽')) ?: '₽',
                'sort'       => ($r['sort'] ?? '') !== '' ? (int)$r['sort'] : 1000,
                'is_active'  => isset($r['is_active']) ? (bool)$r['is_active'] : true,
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
            // теги должны быть массивами, но на всякий случай держим fallback
            if (is_array($t)) {
                return [
                    'title' => trim((string)($t['title'] ?? '')),
                    'sort'  => isset($t['sort']) && $t['sort'] !== '' ? (int)$t['sort'] : 1000,
                ];
            }

            return [
                'title' => trim((string)$t),
                'sort'  => 1000,
            ];
        })
            ->filter(fn($r) => $r['title'] !== '')
            ->values();

        $car->tags()->delete();

        if ($rows->isEmpty()) return;

        $car->tags()->createMany($rows->all());
    }
}
