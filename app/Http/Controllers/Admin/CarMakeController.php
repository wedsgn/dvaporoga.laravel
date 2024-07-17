<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CarMake\StoreRequest;
use App\Http\Requests\Admin\CarMake\UpdateRequest;
use App\Models\CarMake;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CarMakeController extends BaseController
{
  public function index()
  {
      $user = Auth::user();
      $car_makes = CarMake::orderBy('id', 'DESC')->paginate(50);
      return view('admin.car_makes.index', compact('car_makes', 'user'));
  }

  public function show($car_make_slug)
  {
      $user = Auth::user();
      $item = CarMake::whereSlug($car_make_slug)->firstOrFail();
      return view('admin.car_makes.show', compact('item', 'user'));
  }

  public function create()
  {
      $user = Auth::user();

      return view('admin.car_makes.create', compact('user'));
  }
  public function store(StoreRequest $request)
  {
      $data = $request->validated();
      $data['slug'] = Str::slug($data['title']);

      if ($request->hasFile('image')) :
          $data['image'] = $this->upload_service->imageConvertAndStore($request, $data['image'], $data['slug']);
      endif;
      if ($request->hasFile('image_mob')) :
          $data['image_mob'] = $this->upload_service->imageConvertAndStore($request, $data['image_mob'], $data['slug']);
      endif;
      CarMake::firstOrCreate($data);

      return redirect()->route('admin.car_makes.index')->with('status', 'item-created');
  }
  public function edit($car_make_slug)
  {
      $user = Auth::user();
      $item = CarMake::whereSlug($car_make_slug)->firstOrFail();

      return view('admin.car_makes.edit', compact('user', 'item'));
  }
  public function update(UpdateRequest $request, $car_make_slug)
  {
      $car_make = CarMake::whereSlug($car_make_slug)->firstOrFail();
      $data = $request->validated();
      $data['slug'] = Str::slug($data['title']);

      if ($request->hasFile('image')) :
          $data['image'] = $this->upload_service->imageConvertAndStore($request, $data['image'], $data['slug']);
      endif;
      if ($request->hasFile('image_mob')) :
          $data['image_mob'] = $this->upload_service->imageConvertAndStore($request, $data['image_mob'], $data['slug']);
      endif;

      $car_make->update($data);
      return redirect()->route('admin.car_makes.index')->with('status', 'item-updated');
  }

  public function destroy($car_make_slug)
  {
      $car_make = CarMake::whereSlug($car_make_slug)->firstOrFail();
      // $car_make->delete_files($car_make);
      $car_make->delete();
      return redirect()->route('admin.car_makes.index')->with('status', 'item-deleted');
  }

  public function search(Request $request)
  {
      $user = Auth::user();
      if (request('search') == null) :
          $car_makes = CarMake::orderBy('id', 'DESC')->paginate(50);
      else :
          $car_makes = CarMake::filter()->paginate(50);
      endif;
      return view('admin.car_makes.index', compact('car_makes', 'user'));
  }
}
