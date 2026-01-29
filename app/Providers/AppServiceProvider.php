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

use App\Observers\CarMakeObserver;
use App\Observers\CarModelObserver;
use App\Observers\CarObserver;
use App\Observers\ProductObserver;

use App\Support\Feeds\MarkYandexFeedDirty;

use Illuminate\Support\Facades\DB;
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

            'main_info' => MainInfo::first(),
        ]);

        Car::observe(CarObserver::class);
        Product::observe(ProductObserver::class);
        CarModel::observe(CarModelObserver::class);
        CarMake::observe(CarMakeObserver::class);

        DB::listen(function ($query) {
            $sql = strtolower((string)($query->sql ?? ''));

            if ($sql === '' || !str_contains($sql, 'car_product')) {
                return;
            }

            if (
                str_starts_with($sql, 'insert') ||
                str_starts_with($sql, 'update') ||
                str_starts_with($sql, 'delete')
            ) {
                MarkYandexFeedDirty::mark();
            }
        });
    }
}
