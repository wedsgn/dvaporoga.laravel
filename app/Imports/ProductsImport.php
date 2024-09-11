<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Size;
use App\Models\SteelType;
use App\Models\Thickness;
use App\Models\Type;
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

      if (!Product::whereSlug(Str::slug(trim($row[1])))->exists()) :
        $product = Product::create([
          'title' => trim($row[1]),
          'slug' => Str::slug(trim($row[1])),
          'image' => 'default',
          'image_mob' => 'default',

          'description' => 'Ремкомплекты порогов предназначены для ремонта внешних порогов при корозии,
           а также деформации и незначительном повреждении при ДТП.
           Изготавливаются из холоднокатаной и оцинкованной стали длиной в 2 метра',
        ]);
      else:
        $product = Product::whereSlug(Str::slug($row[1]))->first();
      endif;
      if (!empty(trim($row[2]))) :
        if (!Type::whereSlug(Str::slug(trim($row[2])))->exists()) :
          $type = Type::create([
            'title' => trim($row[2]),
            'slug' => Str::slug(trim($row[2])),
          ]);
        endif;
        if(Type::whereSlug(Str::slug(trim($row[3])))->exists()):
          $type = Type::whereSlug(Str::slug(trim($row[2])))->first();
        endif;
        if (!$product->types()->where('type_id', $type->id)->exists()) {
          $product->types()->attach($type->id);
        }
      endif;

      if (!empty(trim($row[3]))) :
        if (!Thickness::whereSlug(Str::slug(trim($row[3])))->exists()) :
          $thickness = Thickness::create([
            'title' => trim($row[3]),
            'slug' => Str::slug(trim($row[3])),
          ]);
        endif;
        if(Thickness::whereSlug(Str::slug(trim($row[3])))->exists()):
          $thickness = Thickness::whereSlug(Str::slug(trim($row[3])))->first();
        endif;

        if (!$product->thicknesses()->where('thickness_id', $thickness->id)->exists()) {
          $product->thicknesses()->attach($thickness->id);
        }
      endif;

      if (!empty(trim($row[4]))) :
        if (!Size::whereSlug(Str::slug(trim($row[4])))->exists()) :
          $size = Size::create([
            'title' => trim($row[4]),
            'slug' => Str::slug(trim($row[4])),
          ]);
        endif;
        if(Size::whereSlug(Str::slug(trim($row[4])))->exists()):
          $size = Size::whereSlug(Str::slug(trim($row[4])))->first();
        endif;

        if (!$product->sizes()->where('size_id', $size->id)->exists()) {
          $product->sizes()->attach($size->id);
        }
      endif;

      if (!empty(trim($row[5]))) :
        if (!SteelType::whereSlug(Str::slug(trim($row[5])))->exists()) :
          $steel_type = SteelType::create([
            'title' => trim($row[5]),
            'slug' => Str::slug(trim($row[5])),
          ]);
        endif;
        if(SteelType::whereSlug(Str::slug(trim($row[5])))->exists()):
          $steel_type = SteelType::whereSlug(Str::slug(trim($row[5])))->first();
        endif;

        if (!$product->steel_types()->where('steel_type_id', $steel_type->id)->exists()) {
          $product->steel_types()->attach($steel_type->id);
        }
      endif;

      $steel_type = $product->prices()->create([
        'one_side' => trim($row[6]),
        'set' => trim($row[7]),
        'thickness_id' => $thickness->id ?? null,
        'steel_type_id' => $steel_type->id ?? null,
        'type_id' => $type->id ?? null,
        'size_id' => $size->id ?? null,
      ]);

    endforeach;
  }
}
