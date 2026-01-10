<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CarMake;

class CatalogProductPageController extends Controller
{
    public function car_generation_show(string $car_make_slug, string $model_slug, string $slug)
    {
        $car_make = CarMake::query()
            ->where('slug', $car_make_slug)
            ->firstOrFail();

        $car_model = $car_make->car_models()
            ->where('slug', $model_slug)
            ->firstOrFail();

        $car = $car_model->cars()
            ->where('slug', $slug)
            ->with([
                'tags',
                'offers',

                'products' => function ($q) {
                    $q->orderBy('sort')->orderBy('id');
                },
            ])
            ->firstOrFail();

        return view('catalog_products', [
            'car' => $car,
            'products' => $car->products,
        ]);
    }
}
