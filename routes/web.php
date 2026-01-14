<?php

use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\CarMakeController;
use App\Http\Controllers\Admin\CarModelController;
use App\Http\Controllers\Admin\EditorImageUploadController;
use App\Http\Controllers\Admin\ImportExelController;
use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductFeaturesController;
use App\Http\Controllers\Admin\RequestConsultationController;
use App\Http\Controllers\Admin\RequestProductController;
use App\Http\Controllers\Client\BlogPageController;
use App\Http\Controllers\Client\CatalogConcernPageController;
use App\Http\Controllers\Client\CatalogGenerationPageController;
use App\Http\Controllers\Client\CatalogModelPageController;
use App\Http\Controllers\Client\RequestsController;
use App\Http\Controllers\Client\WelcomePageController;
use App\Http\Controllers\Client\CarAjaxController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PageBannerController;
use App\Http\Controllers\Admin\ProductCarImagesController;


Route::get('/', [WelcomePageController::class, 'index'])->name('home');
//Concern

Route::get('/katalog', [CatalogConcernPageController::class, 'index'])->name('catalog');
Route::get('/katalog/search', [CatalogConcernPageController::class, 'search'])->name('car_make.search');
Route::get('/katalog/{car_make_slug}', [CatalogConcernPageController::class, 'car_make_show'])->name('car_make.show');
//Car models
Route::get('/katalog/{car_make_slug}/search', [CatalogModelPageController::class, 'search'])->name('car_model.search');
Route::get('/katalog/{slug}/{model_slug}', [CatalogModelPageController::class, 'car_model_show'])->name('car_model.show');

//Car generations
Route::get('/katalog/{concern}/{model}/{generation}', [CatalogGenerationPageController::class, 'car_generation_show'])->name('car_generation.show');
Route::get('/blog', [BlogPageController::class, 'index'])->name('blog');
Route::get('/blog/add-more', [BlogPageController::class, 'add_more'])->name('blog.add_more');
Route::get('/blog/search', [BlogPageController::class, 'search'])->name('blog.search');
Route::get('/blog/{slug}', [BlogPageController::class, 'show'])->name('blog.single');

Route::post('/request-consultation', [RequestsController::class, 'store_request_consultation'])->name('request_consultation.store');
Route::post('/requests/car', [RequestsController::class, 'store_request_car'])->name('requests.car');
Route::post('/request-product', [RequestsController::class, 'store_request_product'])->name('request_product.store');

Route::middleware('auth')->name('admin.')->prefix('admin')->group(function () {

  Route::get('/', [MainController::class, 'index'])->name('index');
  Route::get('/edit_info', [MainController::class, 'edit_info'])->name('edit_info');
  Route::patch('/{main_info_id}/update_info', [MainController::class, 'update_info'])->name('update_info');


  Route::get('/products/{product:slug}/cars', [ProductCarImagesController::class, 'index'])
    ->name('products.cars.index');

  Route::post('/products/{product:slug}/cars/{car}/image', [ProductCarImagesController::class, 'updateImage'])
    ->name('products.cars.image');

  Route::post('/editor-uploads', EditorImageUploadController::class)->name('image_upload');

  Route::get('/import_catalog', [\App\Http\Controllers\Admin\CatalogImportController::class, 'index'])->name('import.catalog');
  Route::post('/import_catalog/upload', [\App\Http\Controllers\Admin\CatalogImportController::class, 'upload'])->name('import.catalog.upload');
  Route::post('/import_catalog/start', [\App\Http\Controllers\Admin\CatalogImportController::class, 'start'])->name('import.catalog.start');
  Route::post('/import_catalog/resume', [\App\Http\Controllers\Admin\CatalogImportController::class, 'resume'])->name('import.catalog.resume');
  Route::post('/import_catalog/pause', [\App\Http\Controllers\Admin\CatalogImportController::class, 'pause'])->name('import.catalog.pause');
  Route::post('/import_catalog/clear-logs', [\App\Http\Controllers\Admin\CatalogImportController::class, 'clearLogs'])->name('import.catalog.clearLogs');
  Route::get('/import_catalog/status', [\App\Http\Controllers\Admin\CatalogImportController::class, 'status'])->name('import.catalog.status');
  Route::post('/import_catalog/cleanup', [\App\Http\Controllers\Admin\CatalogImportController::class, 'cleanup'])
    ->name('import.catalog.cleanup');

Route::get('/import-catalog/download/{run}', [\App\Http\Controllers\Admin\CatalogImportController::class, 'download'])
    ->name('import_catalog.download');


  Route::name('pages.')->prefix('pages')->group(function () {
    Route::get('/', [PageController::class, 'index'])->name('index');
    Route::get('/search', [PageController::class, 'search'])->name('search');
    Route::get('/{page_slug}', [PageController::class, 'show'])->name('show');
    Route::get('/{page_slug}/edit', [PageController::class, 'edit'])->name('edit');
    Route::patch('/{page_slug}', [PageController::class, 'update'])->name('update');
  });

  Route::post('page-banners/{page}', [PageBannerController::class, 'store'])
    ->name('page-banners.store');
  Route::get('page-banners/{banner}/edit', [PageBannerController::class, 'edit'])
    ->name('page-banners.edit');
  Route::patch('page-banners/{banner}', [PageBannerController::class, 'update'])
    ->name('page-banners.update');
  Route::delete('page-banners/{banner}', [PageBannerController::class, 'destroy'])
    ->name('page-banners.destroy');

  Route::name('car_makes.')->prefix('car_makes')->group(function () {
    Route::get('/', [CarMakeController::class, 'index'])->name('index');
    Route::get('/search', [CarMakeController::class, 'search'])->name('search');
    Route::get('/create', [CarMakeController::class, 'create'])->name('create');
    Route::post('/store', [CarMakeController::class, 'store'])->name('store');
    Route::get('/{car_make_slug}', [CarMakeController::class, 'show'])->name('show');
    Route::get('/{car_make_slug}/edit', [CarMakeController::class, 'edit'])->name('edit');
    Route::patch('/{car_make_slug}', [CarMakeController::class, 'update'])->name('update');
    Route::delete('/{car_make_slug}', [CarMakeController::class, 'destroy'])->name('destroy');
  });

  Route::name('car_makes_order.')->prefix('car_makes_order')->group(function () {
    Route::get('/edit', [CarMakeController::class, 'order'])->name('order');
    Route::get('/{order}', [CarMakeController::class, 'show_order'])->name('show');
    Route::patch('/{order}', [CarMakeController::class, 'update_order'])->name('update_order');
  });

  Route::name('car_models.')->prefix('car_models')->group(function () {
    Route::get('/', [CarModelController::class, 'index'])->name('index');
    Route::get('/search', [CarModelController::class, 'search'])->name('search');
    Route::get('/create', [CarModelController::class, 'create'])->name('create');
    Route::post('/store', [CarModelController::class, 'store'])->name('store');
    Route::get('/{car_model_slug}', [CarModelController::class, 'show'])->name('show');
    Route::get('/{car_model_slug}/edit', [CarModelController::class, 'edit'])->name('edit');
    Route::patch('/{car_model_slug}', [CarModelController::class, 'update'])->name('update');
    Route::delete('/{car_model_slug}', [CarModelController::class, 'destroy'])->name('destroy');
  });
  Route::name('cars.')->prefix('cars')->group(function () {
    Route::get('/', [CarController::class, 'index'])->name('index');
    Route::get('/search', [CarController::class, 'search'])->name('search');
    Route::get('/create', [CarController::class, 'create'])->name('create');
    Route::post('/store', [CarController::class, 'store'])->name('store');
    Route::get('/{car_slug}', [CarController::class, 'show'])->name('show');
    Route::get('/{car_slug}/edit', [CarController::class, 'edit'])->name('edit');
    Route::patch('/{car_slug}', [CarController::class, 'update'])->name('update');
    Route::delete('/{car_slug}', [CarController::class, 'destroy'])->name('destroy');
  });
  Route::name('products.')->prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/search', [ProductController::class, 'search'])->name('search');
    Route::get('/create', [ProductController::class, 'create'])->name('create');
    Route::post('/store', [ProductController::class, 'store'])->name('store');
    Route::get('/{product_slug}', [ProductController::class, 'show'])->name('show');
    Route::get('/{product_slug}/edit', [ProductController::class, 'edit'])->name('edit');
    Route::patch('/{product_slug}', [ProductController::class, 'update'])->name('update');
    Route::delete('/{product_slug}', [ProductController::class, 'destroy'])->name('destroy');
    Route::name('prices.')->prefix('prices')->group(function () {
      Route::get('/{product_slug}/create', [ProductFeaturesController::class, 'priceCreate'])->name('priceCreate');
      Route::post('/{product_slug}', [ProductFeaturesController::class, 'priceStore'])->name('priceStore');
      Route::get('/{product_slug}/{price_id}/edit', [ProductFeaturesController::class, 'priceEdit'])->name('priceEdit');
      Route::patch('/{product_slug}/{price_id}', [ProductFeaturesController::class, 'priceUpdate'])->name('priceUpdate');
      Route::delete('/{product_slug}/{price_id}', [ProductFeaturesController::class, 'priceDestroy'])->name('priceDestroy');
    });
    Route::name('sizes.')->prefix('sizes')->group(function () {
      Route::get('/{product_slug}/create', [ProductFeaturesController::class, 'sizeCreate'])->name('sizeCreate');
      Route::post('/{product_slug}', [ProductFeaturesController::class, 'sizeStore'])->name('sizeStore');
      Route::get('/{product_slug}/{size_id}/edit', [ProductFeaturesController::class, 'sizeEdit'])->name('sizeEdit');
      Route::patch('/{product_slug}/{size_id}', [ProductFeaturesController::class, 'sizeUpdate'])->name('sizeUpdate');
      Route::delete('/{product_slug}/{size_id}', [ProductFeaturesController::class, 'sizeDestroy'])->name('sizeDestroy');
    });
    Route::name('steel_types.')->prefix('steel_types')->group(function () {
      Route::get('/{product_slug}/create', [ProductFeaturesController::class, 'steelTypeCreate'])->name('steelTypeCreate');
      Route::post('/{product_slug}', [ProductFeaturesController::class, 'steelTypeStore'])->name('steelTypeStore');
      Route::get('/{product_slug}/{steel_type_id}/edit', [ProductFeaturesController::class, 'steelTypeEdit'])->name('steelTypeEdit');
      Route::patch('/{product_slug}/{steel_type_id}', [ProductFeaturesController::class, 'steelTypeUpdate'])->name('steelTypeUpdate');
      Route::delete('/{product_slug}/{steel_type_id}', [ProductFeaturesController::class, 'steelTypeDestroy'])->name('steelTypeDestroy');
    });
    Route::name('thicknesses.')->prefix('thicknesses')->group(function () {
      Route::get('/{product_slug}/create', [ProductFeaturesController::class, 'thicknessCreate'])->name('thicknessCreate');
      Route::post('/{product_slug}', [ProductFeaturesController::class, 'thicknessStore'])->name('thicknessStore');
      Route::get('/{product_slug}/{thickness_id}/edit', [ProductFeaturesController::class, 'thicknessEdit'])->name('thicknessEdit');
      Route::patch('/{product_slug}/{thickness_id}', [ProductFeaturesController::class, 'thicknessUpdate'])->name('thicknessUpdate');
      Route::delete('/{product_slug}/{thickness_id}', [ProductFeaturesController::class, 'thicknessDestroy'])->name('thicknessDestroy');
    });
    Route::name('types.')->prefix('types')->group(function () {
      Route::get('/{product_slug}/create', [ProductFeaturesController::class, 'typeCreate'])->name('typeCreate');
      Route::post('/{product_slug}', [ProductFeaturesController::class, 'typeStore'])->name('typeStore');
      Route::get('/{product_slug}/{type_id}/edit', [ProductFeaturesController::class, 'typeEdit'])->name('typeEdit');
      Route::patch('/{product_slug}/{type_id}', [ProductFeaturesController::class, 'typeUpdate'])->name('typeUpdate');
      Route::delete('/{product_slug}/{type_id}', [ProductFeaturesController::class, 'typeDestroy'])->name('typeDestroy');
    });
  });

  Route::name('blogs.')->prefix('blogs')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/search', [BlogController::class, 'search'])->name('search');
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


Route::get('single-car', function () {
  return view('car-sigle');
});
