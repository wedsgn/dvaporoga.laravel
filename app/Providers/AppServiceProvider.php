<?php

namespace App\Providers;

use App\Models\Blog;
use App\Models\Car;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\Product;
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
      View::share('car_makes_routes', CarMake::$car_makes_routes);
      View::share('car_models_routes', CarModel::$car_models_routes);
      View::share('cars_routes', Car::$cars_routes);
      View::share('products_routes', Product::$products_routes);
      View::share('blogs_routes', Blog::$blogs_routes);
    }
}
