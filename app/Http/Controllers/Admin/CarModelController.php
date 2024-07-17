<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CarModel\StoreRequest;
use App\Http\Requests\Admin\CarModel\UpdateRequest;
use App\Models\CarMake;
use App\Models\CarModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CarModelController extends BaseController
{
  public function index()
  {
      $user = Auth::user();
      $car_models = CarModel::orderBy('id', 'DESC')->paginate(50);
      return view('admin.car_models.index', compact('car_models', 'user'));
  }

  public function show($car_model_slug)
  {
      $user = Auth::user();
      $item = CarModel::whereSlug($car_model_slug)->firstOrFail();

      return view('admin.car_models.show', compact('item', 'user'));
  }

  public function create()
  {
      $user = Auth::user();
      $car_makes = CarMake::all();

      return view('admin.car_models.create', compact('user','car_makes'));
  }
  public function store(StoreRequest $request)
  {
      $data = $request->validated();
      $data = $this->format_data_service->changeTitleToId($data, CarMake::class, 'car_make_id');
      $data['slug'] = Str::slug($data['title']);

      if ($request->hasFile('image')) :
          $data['image'] = $this->upload_service->imageConvertAndStore($request, $data['image'], $data['slug']);
      endif;
      if ($request->hasFile('image_mob')) :
          $data['image_mob'] = $this->upload_service->imageConvertAndStore($request, $data['image_mob'], $data['slug']);
      endif;
      CarModel::firstOrCreate($data);

      return redirect()->route('admin.car_models.index')->with('status', 'item-created');
  }
  public function edit($car_model_slug)
  {
      $user = Auth::user();
      $item = CarModel::whereSlug($car_model_slug)->firstOrFail();
      $car_makes = CarMake::all();

      return view('admin.car_models.edit', compact('user', 'item','car_makes'));
  }
  public function update(UpdateRequest $request, $car_model_slug)
  {
      $car_model = CarModel::whereSlug($car_model_slug)->firstOrFail();
      $data = $request->validated();
      $data = $this->format_data_service->changeTitleToId($data, CarMake::class, 'car_make_id');
      $data['slug'] = Str::slug($data['title']);

      if ($request->hasFile('image')) :
          $data['image'] = $this->upload_service->imageConvertAndStore($request, $data['image'], $data['slug']);
      endif;
      if ($request->hasFile('image_mob')) :
          $data['image_mob'] = $this->upload_service->imageConvertAndStore($request, $data['image_mob'], $data['slug']);
      endif;

      $car_model->update($data);
      return redirect()->route('admin.car_models.index')->with('status', 'item-updated');
  }

  public function destroy($car_model_slug)
  {
      $car_model = CarModel::whereSlug($car_model_slug)->firstOrFail();
      // $car_model->delete_files($car_model);
      $car_model->delete();
      return redirect()->route('admin.car_models.index')->with('status', 'item-deleted');
  }

  public function search(Request $request)
  {
      $user = Auth::user();
      if (request('search') == null) :
          $car_models = CarModel::orderBy('id', 'DESC')->paginate(50);
      else :
          $car_models = CarModel::filter()->paginate(50);
      endif;
      return view('admin.car_models.index', compact('car_models', 'user'));
  }
}
