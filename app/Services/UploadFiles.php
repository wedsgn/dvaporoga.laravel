<?php

namespace App\Services;

use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;

class UploadFiles
{

  public function imageConvertAndStore($request, $data, $id_or_slug)
  {
    if ($request->is('*/car_makes/*')) {
      $directory = 'uploads/car_makes/' . $id_or_slug . '/images/';
    }
    if ($request->is('*/car_models/*')) {
      $directory = 'uploads/car_models/' . $id_or_slug . '/images/';
    }
    if ($request->is('*/cars/*')) {
      $directory = 'uploads/cars/' . $id_or_slug . '/images/';
    }
    if ($request->is('*/products/*')) {
      $directory = 'uploads/products/' . $id_or_slug . '/images/';
    }
    if ($request->is('*/blogs/*')) {
      $directory = 'uploads/blogs/' . $id_or_slug . '/images/';
    }
    if ($request->is('*/update_info*')) {
      $directory = 'uploads/main_infos/' . $id_or_slug . '/images/';
    }
    if ($request->is('*/page-banners/*')) {
      $directory = 'uploads/page_banners/' . $id_or_slug . '/images/';
    }
    $defaultImage = Image::read($data);
    $filename = Str::ulid() . '.webp';
    $path = $directory . $filename;
    $defaultImage = $defaultImage->toWebp(80);
    Storage::disk('public')->put($path, (string)$defaultImage);
    return $path;
  }

  public function videoStore($request, $data, $id_or_slug)
  {
    if ($request->is('*/blogs/*')) {
      $directory = 'uploads/blogs/' . $id_or_slug . '/videos/';
    }
    $filenameWithExt = $data->getClientOriginalName();
    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
    $filename = str_replace(' ', '_', $filename);
    $extention = $data->getClientOriginalExtension();
    $fileNameToStore = $directory . $filename . "_" . time() . "." . $extention;
    $data = $data->storeAs('public', $fileNameToStore);
    return $fileNameToStore;
  }
}
