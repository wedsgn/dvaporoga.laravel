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
            'description' => '<p>Ремонтные арки и пороги для ' . $row[1] . ' с доставкой по всей России.</p>

            <p>100% повторение оригинала. Изготовлено на современном обрудовании с учетом всех форм и изгибов
            оригинальных авто. Выберите модель автомобиля.</p>',
            'meta_title' => 'Ремонтные арки и пороги для ' . trim($row[1]),
            'meta_description' => 'Ремонтные арки и пороги для ' . $row[1] . '. 100% повторение оригинала. Изготовлено на современном обрудовании с учетом всех форм и изгибов.',
            'meta_keywords' => '',
            'og_title' => 'Ремонтные арки и пороги для ' . trim($row[1]),
            'og_description' => 'Ремонтные арки и пороги для ' . $row[1] . '. 100% повторение оригинала. Изготовлено на современном обрудовании с учетом всех форм и изгибов.',
            'og_url' => 'https://dvaporoga.ru/katalog/'. Str::slug(trim($row[1])),
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
              'description' => 'Ремкомплект изготавливается с учетом всех форм и изгибов оригинальных авто ' . trim($row[1]) . ' ' . $title_car_model . '.
               Выберите поколение автомобиля и перейдите к заказу.',
              'car_make_id' => CarMake::whereSlug(Str::slug(trim($row[1])))->first()->id,
              'meta_title' => 'Ремонтные детали для восстановления кузова ' . trim($row[1]) . ' ' . $title_car_model . '.',
              'meta_description' => 'Ремкомплект изготавливается с учетом всех форм и изгибов оригинальных авто ' . trim($row[1]) . ' ' . $title_car_model . '.',
              'meta_keywords' => '',
              'og_title' => 'Ремонтные детали для восстановления кузова ' . trim($row[1]) . ' ' . $title_car_model . '.',
              'og_description' => 'Ремкомплект изготавливается с учетом всех форм и изгибов оригинальных авто ' . trim($row[1]) . ' ' . $title_car_model . '.',
              'og_url' => 'https://dvaporoga.ru/katalog/'. Str::slug(trim($row[1])) . '/' . $slug_car_model,
            ]);
          endif;
        endif;

      if (!Car::whereSlug(Str::slug(trim($row[5]) . ' ' . trim($row[3]) . ' ' . trim($row[4])))->exists()) :
        if ($slug_car_model == CarModel::whereSlug($slug_car_model)->first()->slug) :
          $car = Car::create([
            'title' => trim($row[1]) . ' ' . $title_car_model . ' ' . trim($row[5]) . ' ' . trim($row[3]),
            'slug' => Str::slug(trim($row[1]) . ' ' . $title_car_model . ' ' . trim($row[5]) . ' ' . trim($row[3])),
            'image' => $row[0],
            'image_mob' => 'default',
            'generation' => $row[3],
            'years' => $row[4],
            'body' => (preg_match('/\((.+)\)/', $row[2], $match) ? trim($match[1]) : null),
            'top' => null,
            'artikul' => null,
            'description' => 'Ремкомплекты для осуществления необходимого ремонта в поврежденных коррозией или авариями частей кузова автомобиля ' . trim($row[1]) . ' ' . $title_car_model . ' ' . trim($row[5]) . '.
               В данном разделе Вы можете ознакомится с колесными арками, внутренними элементами арок для сварки новых элементов, кузовными порогами и усилителями к ним.',
            'car_model_id' => CarModel::whereSlug($slug_car_model)->first()->id,
            'meta_title' => 'Купить ремонтные детали для ' . trim($row[1]) . ' ' . $title_car_model . '.',
            'meta_description' => 'Ремкомплекты для осуществления необходимого ремонта в поврежденных коррозией или авариями частей кузова ' . trim($row[1]) . ' ' . $title_car_model . ' ' . trim($row[5]) . '.',
            'meta_keywords' => '',
            'og_title' => 'Купить ремонтные детали для ' . trim($row[1]) . ' ' . $title_car_model . '.',
            'og_description' => 'Ремкомплекты для осуществления необходимого ремонта в поврежденных коррозией или авариями частей кузова ' . trim($row[1]) . ' ' . $title_car_model . '.',
            'og_url' => 'https://dvaporoga.ru/katalog/'. Str::slug(trim($row[1])) . '/' . $slug_car_model . '/' . Str::slug(trim($row[1]) . ' ' . $title_car_model . ' ' . trim($row[5]) . ' ' . trim($row[3])),
          ]);

          $products = Product::all();
          $car->products()->attach($products);
        endif;
      endif;
    endforeach;

  }
}
