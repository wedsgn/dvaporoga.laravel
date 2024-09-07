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

        if (!CarMake::whereSlug(Str::slug($row[1]))->exists()) :
          $car_make = CarMake::create([
            'title' => trim($row[1]),
            'slug' => Str::slug(trim($row[1])),
            'image' => 'default',
            'image_mob' => 'default',
            'description' => '<p>Ремонтные арки и пороги для автомобилей ' . $row[1] . ' с бесплатной доставкой по России.</p>

        <p>100% повторение оригинала. Изготовлено на современном обрудовании с учетом всех форм и изгибов
            оригинальных автомобилей ' . $row[1] . '. Выберите свою модель и перейдите к заказу.</p>',
          ]);
        endif;

        $title_car_model = preg_replace('/\s*\([^)]+\)/', '', $row[2]);
        $slug_car_model = Str::slug(trim(preg_replace('/\s*\([^)]+\)/', '', $row[2])));

        if (!CarModel::whereSlug($slug_car_model)->exists()) :
          if (Str::slug($row[1]) == CarMake::whereSlug(Str::slug($row[1]))->first()->slug) :
            $car_model = CarModel::create([
              'title' => $title_car_model,
              'slug' => $slug_car_model,
              'image' => $row[0],
              'image_mob' => 'default',
              'description' => 'Ремонтные детали для восстановления кузова автомобиля марки ' . $title_car_model . ' с доставкой по всей России.
                 Ремкомплект изготавливается с учетом всех форм и изгибов оригинальных автомобилей ' . $title_car_model . '. Выберите свою модель и перейдите к заказу.',
              'car_make_id' => CarMake::whereSlug(Str::slug(trim($row[1])))->first()->id
            ]);
          endif;
        endif;
      if (!Car::whereSlug(Str::slug(trim($row[5]) . ' ' . trim($row[3]) . ' ' . trim($row[4])))->exists()) :
        if ($slug_car_model == CarModel::whereSlug($slug_car_model)->first()->slug) :
          $car = Car::create([
            'title' => $row[5],
            'slug' => Str::slug(trim($row[1]) . ' ' . $title_car_model . ' ' . trim($row[5]) . ' ' . trim($row[3])),
            'image' => $row[0],
            'image_mob' => 'default',
            'generation' => $row[3],
            'years' => $row[4],
            'body' => (preg_match('/\((.+)\)/', $row[2], $match) ? trim($match[1]) : null),
            'top' => null,
            'artikul' => null,
            'description' => 'Ремонтные детали для восстановления кузова автомобиля марки ' . trim($row[5]) . ' с доставкой по всей России.
               Ремкомплект изготавливается с учетом всех форм и изгибов оригинальных автомобилей ' . trim($row[5]) . '. Выберите свою модель и перейдите к заказу.',
            'car_model_id' => CarModel::whereSlug($slug_car_model)->first()->id
          ]);
          // if ($row[7] == true) :
          //   $products = Product::whereSlug('porog-standartnyi')->get();
          //   $car->products()->attach($products);
          // endif;
          // if ($row[8] == true) :
          //   $products = Product::whereSlug('porog-uvelicennyi-v-proem')->get();
          //   $car->products()->attach($products);
          // endif;
          // if ($row[9] == true) :
          //   $products = Product::whereSlug('arka-remontnaia-zadniaia')->get();
          //   $car->products()->attach($products);
          // endif;
          // if ($row[10] == true) :
          //   $products = Product::whereSlug('arka-remontnaia-peredniaia')->get();
          //   $car->products()->attach($products);
          // endif;
          // if ($row[11] == true) :
          //   $products = Product::whereSlug('arka-remontnaia-vnutrenniaia-zadniaia')->get();
          //   $car->products()->attach($products);
          // endif;
          // if ($row[12] == true) :
          //   $products = Product::whereSlug('arka-remontnaia-vnutrenniaia-peredniaia')->get();
          //   $car->products()->attach($products);
          // endif;
          // if ($row[13] == true) :
          //   $products = Product::whereSlug('remontnyi-komplekt-dverei-peredniaia-penka')->get();
          //   $car->products()->attach($products);
          // endif;
          // if ($row[14] == true) :
          //   $products = Product::whereSlug('remontnyi-komplekt-dverei-zadniaia-penka')->get();
          //   $car->products()->attach($products);
          // endif;

          // $products = Product::whereNotIn('slug', [
          //   'porog-standartnyi',
          //   'porog-uvelicennyi-v-proem',
          //   'arka-remontnaia-zadniaia',
          //   'arka-remontnaia-vnutrenniaia-zadniaia',
          //   'arka-remontnaia-vnutrenniaia-peredniaia',
          //   'remontnyi-komplekt-dverei-peredniaia-penka',
          //   'remontnyi-komplekt-dverei-zadniaia-penka'
          // ])->get();
          // $car->products()->attach($products);
        endif;
      endif;
    endforeach;
  }
}
