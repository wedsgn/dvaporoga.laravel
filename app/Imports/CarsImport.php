<?php

namespace App\Imports;

use App\Models\Car;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\Product;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;

class CarsImport implements ToCollection
{
  /**
   * @param Collection $collection
   */
  public function collection(Collection $collection)
  {
    foreach ($collection->skip(1) as $row) :

        if (!CarMake::whereSlug(Str::slug($row[0]))->exists()) :
          $car_make = CarMake::create([
            'title' => trim($row[0]) ?? rand(5, 100),
            'slug' => Str::slug($row[0]) ?? rand(5, 100),
            'image' => 'default',
            'image_mob' => 'default',
            'description' => 'Ремонтные детали для восстановления кузова автомобиля марки ' . $row[0] . ' с доставкой по всей России.
                 Ремкомплект изготавливается с учетом всех форм и изгибов оригинальных автомобилей ' . $row[0] . '. Выберите свою модель и перейдите к заказу.',
          ]);
        endif;

        if (!CarModel::whereSlug(Str::slug($row[1]))->exists()) :
          if (Str::slug($row[0]) == CarMake::whereSlug(Str::slug($row[0]))->first()->slug) :
            $car_model = CarModel::create([
              'title' => trim($row[1]),
              'slug' => Str::slug($row[1]),
              'image' => 'default',
              'image_mob' => 'default',
              'description' => 'Ремонтные детали для восстановления кузова автомобиля марки ' . $row[1] . ' с доставкой по всей России.
                 Ремкомплект изготавливается с учетом всех форм и изгибов оригинальных автомобилей ' . $row[1] . '. Выберите свою модель и перейдите к заказу.',
              'car_make_id' => CarMake::whereSlug(Str::slug($row[0]))->first()->id
            ]);
          endif;
        endif;

      if (!Car::whereSlug(Str::slug($row[0] . '-' . $row[1] . '-' . $row[2] . '-' . $row[7]))->exists()) :
        if (Str::slug($row[1]) == CarModel::whereSlug(Str::slug($row[1]))->first()->slug) :
          $car = Car::create([
            'title' => trim($row[0] . ' ' . $row[1] . ' ' . $row[2]),
            'slug' => Str::slug($row[0] . '-' . $row[1] . '-' . $row[2] . '-' . $row[7]),
            'image' => 'default',
            'image_mob' => 'default',
            'generation' => $row[2],
            'years' => $row[3],
            'body' => $row[4],
            'top' => $row[5],
            'artikul' => null,
            'description' => 'Ремонтные детали для восстановления кузова автомобиля марки ' . $row[1] . ' с доставкой по всей России.
               Ремкомплект изготавливается с учетом всех форм и изгибов оригинальных автомобилей ' . $row[1] . '. Выберите свою модель и перейдите к заказу.',
            'car_model_id' => CarModel::whereSlug(Str::slug($row[1]))->first()->id
          ]);
          if ($row[7] == true) :
            $products = Product::whereSlug('porog-standartnyi')->get();
            $car->products()->attach($products);
          endif;
          if ($row[8] == true) :
            $products = Product::whereSlug('porog-uvelicennyi-v-proem')->get();
            $car->products()->attach($products);
          endif;
          if ($row[9] == true) :
            $products = Product::whereSlug('arka-remontnaia-zadniaia')->get();
            $car->products()->attach($products);
          endif;
          if ($row[10] == true) :
            $products = Product::whereSlug('arka-remontnaia-peredniaia')->get();
            $car->products()->attach($products);
          endif;
          if ($row[11] == true) :
            $products = Product::whereSlug('arka-remontnaia-vnutrenniaia-zadniaia')->get();
            $car->products()->attach($products);
          endif;
          if ($row[12] == true) :
            $products = Product::whereSlug('arka-remontnaia-vnutrenniaia-peredniaia')->get();
            $car->products()->attach($products);
          endif;
          if ($row[13] == true) :
            $products = Product::whereSlug('remontnyi-komplekt-dverei-peredniaia-penka')->get();
            $car->products()->attach($products);
          endif;
          if ($row[14] == true) :
            $products = Product::whereSlug('remontnyi-komplekt-dverei-zadniaia-penka')->get();
            $car->products()->attach($products);
          endif;

          $products = Product::whereNotIn('slug', [
            'porog-standartnyi',
            'porog-uvelicennyi-v-proem',
            'arka-remontnaia-zadniaia',
            'arka-remontnaia-vnutrenniaia-zadniaia',
            'arka-remontnaia-vnutrenniaia-peredniaia',
            'remontnyi-komplekt-dverei-peredniaia-penka',
            'remontnyi-komplekt-dverei-zadniaia-penka'
          ])->get();
          $car->products()->attach($products);
        endif;
      endif;
    endforeach;
  }
}
