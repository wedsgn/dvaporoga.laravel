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

      return view('admin.cars.create', compact('user','car_models'));
  }
  public function store(StoreRequest $request)
  {
      $data = $request->validated();
      $data = $this->format_data_service->changeTitleToId($data, CarModel::class, 'car_model_id');
      $data['slug'] = Str::slug($data['title']);

      foreach (['image', 'image_mob'] as $image) {
        if ($request->hasFile($image)) {
            $data[$image] = $this->upload_service->imageConvertAndStore($request, $data[$image], $data['slug']);
        }
    }
      Car::firstOrCreate($data);

      return redirect()->route('admin.cars.index')->with('status', 'item-created');
  }
  public function edit($car_slug)
  {
      $user = Auth::user();
      $item = Car::whereSlug($car_slug)->firstOrFail();
      $car_models = CarModel::all();

      return view('admin.cars.edit', compact('user', 'item','car_models'));
  }
  public function update(UpdateRequest $request, $car_slug)
  {
      $car = Car::whereSlug($car_slug)->firstOrFail();
      $data = $request->validated();
      $data = $this->format_data_service->changeTitleToId($data, CarModel::class, 'car_model_id');
      $data['slug'] = Str::slug($data['title']);

      foreach (['image', 'image_mob'] as $image) {
        if ($request->hasFile($image)) {
            $data[$image] = $this->upload_service->imageConvertAndStore($request, $data[$image], $data['slug']);
        }
    }

      $car->update($data);
      return redirect()->route('admin.cars.index')->with('status', 'item-updated');
  }

  public function destroy($car_slug)
  {

      $car = Car::whereSlug($car_slug)->firstOrFail();
      // $car->delete_files($car);
      $car->delete();
      return redirect()->route('admin.cars.index')->with('status', 'item-deleted');
  }

  public function search(Request $request)
  {
      $user = Auth::user();
      if (request('search') == null) :
          $cars = Car::orderBy('id', 'DESC')->paginate(50);
      else :
          $cars = Car::filter()->paginate(50);
      endif;
      return view('admin.cars.index', compact('cars', 'user'));
  }
}
