<?php

use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\CarMakeController;
use App\Http\Controllers\Admin\CarModelController;
use App\Http\Controllers\Admin\EditorImageUploadController;
use App\Http\Controllers\Admin\ImportExelController;
use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RequestConsultationController;
use App\Http\Controllers\Admin\RequestProductController;
use App\Http\Controllers\Client\BlogPageController;
use App\Http\Controllers\Client\CatalogConcernPageController;
use App\Http\Controllers\Client\CatalogGenerationPageController;
use App\Http\Controllers\Client\CatalogModelPageController;
use App\Http\Controllers\Client\RequestsController;
use App\Http\Controllers\Client\WelcomePageController;

use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomePageController::class, 'index'])->name('home');
//Concern
Route::get('/katalog', [CatalogConcernPageController::class, 'index'])->name('catalog');
Route::get('/katalog/{car_make_slug}', [CatalogConcernPageController::class, 'car_make_show'])->name('car_make.show');
Route::get('/katalog/search', [CatalogConcernPageController::class, 'search'])->name('catalog.search');
//Car models
Route::get('/katalog/{slug}/{model_slug}/', [CatalogModelPageController::class, 'car_model_show'])->name('car_model.show');
Route::get('/katalog/{car_make_slug}/car_models/search', [CatalogModelPageController::class, 'search'])->name('car_model.search');
//Car generations
Route::get('/katalog/{concern}/{model}/{generation}', [CatalogGenerationPageController::class, 'car_generation_show'])->name('car_generation.show');
Route::get('/blog', [BlogPageController::class, 'index'])->name('blog');
Route::get('/blog/add-more', [BlogPageController::class, 'add_more'])->name('blog.add_more');
Route::get('/blog/search', [BlogPageController::class, 'search'])->name('blog.search');
Route::get('/blog/{slug}', [BlogPageController::class, 'show'])->name('blog.single');

Route::post('/request-consultation', [RequestsController::class, 'store_request_consultation'])->name('request_consultation.store');
Route::post('/request-product', [RequestsController::class, 'store_request_product'])->name('request_product.store');

Route::middleware('auth')->name('admin.')->prefix('admin')->group(function () {

  Route::get('/', [MainController::class, 'index'])->name('index');
  Route::get('/edit_info', [MainController::class, 'edit_info'])->name('edit_info');
  Route::patch('/{main_info_id}/update_info', [MainController::class, 'update_info'])->name('update_info');


  Route::post('/editor-uploads', EditorImageUploadController::class)->name('image_upload');

  Route::get('/import_cars', [ImportExelController::class, 'import_cars'])->name('import_cars');
  Route::get('/import_products', [ImportExelController::class, 'import_products'])->name('import_products');
  Route::post('/import_cars_excel_file', [ImportExelController::class, 'store_cars'])->name('store_cars');
  Route::post('/import_products_excel_file', [ImportExelController::class, 'store_products'])->name('store_products');

  Route::name('car_makes.')->prefix('car_makes')->group(function () {
    Route::get('/', [CarMakeController::class, 'index'])->name('index');
    Route::get('/search',  [CarMakeController::class, 'search'])->name('search');
    Route::get('/create', [CarMakeController::class, 'create'])->name('create');
    Route::post('/store', [CarMakeController::class, 'store'])->name('store');
    Route::get('/{car_make_slug}', [CarMakeController::class, 'show'])->name('show');
    Route::get('/{car_make_slug}/edit', [CarMakeController::class, 'edit'])->name('edit');
    Route::patch('/{car_make_slug}', [CarMakeController::class, 'update'])->name('update');
    Route::delete('/{car_make_slug}', [CarMakeController::class, 'destroy'])->name('destroy');
  });
  Route::name('car_models.')->prefix('car_models')->group(function () {
    Route::get('/', [CarModelController::class, 'index'])->name('index');
    Route::get('/search',  [CarModelController::class, 'search'])->name('search');
    Route::get('/create', [CarModelController::class, 'create'])->name('create');
    Route::post('/store', [CarModelController::class, 'store'])->name('store');
    Route::get('/{car_model_slug}', [CarModelController::class, 'show'])->name('show');
    Route::get('/{car_model_slug}/edit', [CarModelController::class, 'edit'])->name('edit');
    Route::patch('/{car_model_slug}', [CarModelController::class, 'update'])->name('update');
    Route::delete('/{car_model_slug}', [CarModelController::class, 'destroy'])->name('destroy');
  });
  Route::name('cars.')->prefix('cars')->group(function () {
    Route::get('/', [CarController::class, 'index'])->name('index');
    Route::get('/search',  [CarController::class, 'search'])->name('search');
    Route::get('/create', [CarController::class, 'create'])->name('create');
    Route::post('/store', [CarController::class, 'store'])->name('store');
    Route::get('/{car_slug}', [CarController::class, 'show'])->name('show');
    Route::get('/{car_slug}/edit', [CarController::class, 'edit'])->name('edit');
    Route::patch('/{car_slug}', [CarController::class, 'update'])->name('update');
    Route::delete('/{car_slug}', [CarController::class, 'destroy'])->name('destroy');
  });
  Route::name('products.')->prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/search',  [ProductController::class, 'search'])->name('search');
    Route::get('/create', [ProductController::class, 'create'])->name('create');
    Route::post('/store', [ProductController::class, 'store'])->name('store');
    Route::get('/{product_slug}', [ProductController::class, 'show'])->name('show');
    Route::get('/{product_slug}/edit', [ProductController::class, 'edit'])->name('edit');
    Route::patch('/{product_slug}', [ProductController::class, 'update'])->name('update');
    Route::delete('/{product_slug}', [ProductController::class, 'destroy'])->name('destroy');
  });

  Route::name('blogs.')->prefix('blogs')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/search',  [BlogController::class, 'search'])->name('search');
    Route::get('/create', [BlogController::class, 'create'])->name('create');
    Route::post('/store', [BlogController::class, 'store'])->name('store');
    Route::get('/{blog_slug}', [BlogController::class, 'show'])->name('show');
    Route::get('/{blog_slug}/edit', [BlogController::class, 'edit'])->name('edit');
    Route::patch('/{blog_slug}', [BlogController::class, 'update'])->name('update');
    Route::delete('/{blog_slug}', [BlogController::class, 'destroy'])->name('destroy');
});

Route::prefix('request_consultations')->name('request_consultations.')->group(function () {
    Route::get('/', [RequestConsultationController::class, 'index'])->name('index');
    Route::get('/search', [RequestConsultationController::class, 'search'])->name('search');
    Route::get('/{id}', [RequestConsultationController::class, 'show'])->name('show');
});

Route::prefix('request_products')->name('request_products.')->group(function () {
    Route::get('/', [RequestProductController::class, 'index'])->name('index');
    Route::get('/search', [RequestProductController::class, 'search'])->name('search');
    Route::get('/{id}', [RequestProductController::class, 'show'])->name('show');
});
});
