<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CarMake\StoreRequest;
use App\Http\Requests\Admin\CarMake\UpdateRequest;
use App\Models\CarMake;
use App\Models\Order;
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
      $data['is_hidden'] = (bool) $request->boolean('is_hidden');
      foreach (['image', 'image_mob'] as $image) {
        if ($request->hasFile($image)) {
            $data[$image] = $this->upload_service->imageConvertAndStore($request, $data[$image], $data['slug']);
        }
    }
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
      $data['is_hidden'] = (bool) $request->boolean('is_hidden');
      foreach (['image', 'image_mob'] as $image) {
        if ($request->hasFile($image)) {
            $data[$image] = $this->upload_service->imageConvertAndStore($request, $data[$image], $data['slug']);
        }
    }

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

  public function order(Request $request)
  {
      $user = Auth::user();
      $order = Order::where('title', 'order_car_makes_home_page')->firstOrFail();
      $car_makes = CarMake::all();

      return view('admin.car_makes_order.edit', compact('order', 'user', 'car_makes'));
  }

  public function update_order(Request $request, $order)
  {
      $order = Order::whereId($order)->firstOrFail();
      $data = $request->validate([
          'car_makes' => 'required|array|max:12',
      ], [
          'car_makes.required' => 'Поле "Марки автомобилей в порядке отображения на главной" обязательно для заполнения.',
          'car_makes.array' => 'Поле "Марки автомобилей в порядке отображения на главной" должно быть массивом.',
          'car_makes.max' => 'Максимальное количество элементов в поле "Марки автомобилей в порядке отображения на главной" не может быть больше :max.',
      ]);
      $this->format_data_service->writeDataToTable($order, $data);
      return redirect()->route('admin.car_makes_order.show', $order)->with('status', 'item-updated');
  }

  public function show_order($order)
  {
      $user = Auth::user();
      $item = Order::whereId($order)->firstOrFail();
      return view('admin.car_makes_order.show', compact('item', 'user'));
  }
}
