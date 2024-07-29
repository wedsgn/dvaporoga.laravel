<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;

class ProductsImport implements ToCollection
{
  /**
   * @param Collection $collection
   */
  public function collection(Collection $collection)
  {
    foreach ($collection as $row) :
        $product = Product::create([
          'title' => $row[1],
          'slug' => Str::slug($row[1]),
          'image' => 'default',
          'image_mob' => 'default',
          'price_one_side' => $row[5],
          'price_set' => $row[6],
          'metal_thickness' => $row[2],
          'size' => $row[3],
          'material' => $row[4],

          'description' => 'Ремкомплекты порогов предназначены для ремонта внешних порогов при корозии,
           а также деформации и незначительном повреждении при ДТП.
           Изготавливаются из холоднокатаной и оцинкованной стали длиной в 2 метра',
        ]);
    endforeach;
  }
}
