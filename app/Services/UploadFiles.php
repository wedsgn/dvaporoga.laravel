<?php

namespace App\Services;

use App\Support\UploadValidation;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Throwable;

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
    if ($request->is('*/blocks/*')) {
      $directory = 'uploads/blocks/' . $id_or_slug . '/images/';
    }

    try {
      $defaultImage = Image::read($data);
      $filename = Str::ulid() . '.webp';
      $path = $directory . $filename;
      $defaultImage = $defaultImage->toWebp(80);
      Storage::disk('public')->put($path, (string)$defaultImage);
    } catch (Throwable) {
      $field = $this->resolveImageField($request, $data);

      throw ValidationException::withMessages([
        $field => [UploadValidation::processingFailedMessage(UploadValidation::name($field))],
      ]);
    }

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

  private function resolveImageField($request, $data): string
  {
    foreach (['image', 'image_mob', 'company_image', 'image_desktop', 'image_mobile'] as $field) {
      if ($request->file($field) === $data) {
        return $field;
      }
    }

    foreach ($request->file('new_images', []) as $index => $file) {
      if ($file === $data) {
        return "new_images.{$index}";
      }
    }

    foreach ($request->file('new_items', []) as $index => $pair) {
      foreach (['before', 'after'] as $side) {
        if (($pair[$side] ?? null) === $data) {
          return "new_items.{$index}.{$side}";
        }
      }
    }

    return 'image';
  }
}
