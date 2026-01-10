<?php

namespace App\Providers;

use App\Models\Blog;
use App\Models\Car;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\MainInfo;
use App\Models\Page;
use App\Models\Product;
use App\Models\RequestConsultation;
use App\Models\RequestProduct;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::share([
            'car_makes_routes' => CarMake::$car_makes_routes,
            'car_models_routes' => CarModel::$car_models_routes,
            'cars_routes' => Car::$cars_routes,
            'products_routes' => Product::$products_routes,
            'blogs_routes' => Blog::$blogs_routes,
            'request_consultations_routes' => RequestConsultation::$request_consultations_routes,
            'request_products_routes' => RequestProduct::$request_products_routes,
            'pages_routes' => Page::$pages_routes,

            // MainInfo
            'main_info' => MainInfo::first(),
        ]);
    }
}
