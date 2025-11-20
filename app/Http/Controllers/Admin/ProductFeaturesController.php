<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Price;
use App\Models\Product;
use App\Models\Size;
use App\Models\SteelType;
use App\Models\Thickness;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductFeaturesController extends BaseController
{

// Price =================================================================================================

    public function priceCreate($product_slug)
    {
        $product = Product::where('slug', $product_slug)->first();
        $user = Auth::user();
        $thicknesses = $product->thicknesses;
        $steel_types = $product->steel_types;
        $types = $product->types;
        $sizes =  $product->sizes;
        return view('admin.price.create', compact('product', 'thicknesses', 'steel_types', 'types', 'sizes', 'user'));
    }

    public function priceStore(Request $request, $product_slug)
    {
        $product = Product::whereSlug($product_slug)->firstOrFail();
        $data = $request->validate([
            'one_side' => ['required', 'string', 'max:255'],
            'set' => ['nullable', 'string', 'max:255'],
            'thickness_id' => ['nullable', 'string'],
            'steel_type_id' => ['nullable', 'string'],
            'type_id' => ['nullable', 'string'],
            'size_id' => ['nullable', 'string'],
        ], [
            'one_side.required' => 'Поле "Односторонний" обязательно для заполнения',
            'one_side.string' => 'Поле "Односторонний" должно быть строкой',
            'one_side.max' => 'Поле "Односторонний" должно быть не более 255 символов',
            'set.string' => 'Поле "Комплект" должно быть строкой',
            'set.max' => 'Поле "Комплект" должно быть не более 255 символов',
        ]);
        if ($data['thickness_id'] !== "null") {
          $data = $this->format_data_service->changeTitleToId($data, Thickness::class, 'thickness_id');
        }else{
          $data['thickness_id'] = null;
        }
        if ($data['steel_type_id'] !== "null") {
          $data = $this->format_data_service->changeTitleToId($data, SteelType::class, 'steel_type_id');
        }else{
          $data['steel_type_id'] = null;
        }
        if ($data['type_id'] !== "null") {
          $data = $this->format_data_service->changeTitleToId($data, Type::class, 'type_id');
        }else{
          $data['type_id'] = null;
        }
        if ($data['size_id'] !== "null") {
          $data = $this->format_data_service->changeTitleToId($data, Size::class, 'size_id');
        }else{
          $data['size_id'] = null;
        }
        $product->prices()->create($data);

        return redirect()->route('admin.products.show', $product_slug);
    }

    public function priceEdit($product_slug, $price_id)
    {
        $product = Product::where('slug', $product_slug)->first();
        $price = $product->prices->where('id', $price_id)->first();
        $user = Auth::user();
        $thicknesses = $product->thicknesses;
        $steel_types = $product->steel_types;
        $types = $product->types;
        $sizes =  $product->sizes;
        return view('admin.price.edit', compact('product', 'price', 'thicknesses', 'steel_types', 'types', 'sizes', 'user'));
    }

    public function priceUpdate(Request $request, $product_slug, $price_id)
    {
        $product = Product::where('slug', $product_slug)->first();
        $request->validate([
            'one_side' => ['required', 'string', 'max:255'],
            'set' => ['nullable', 'string', 'max:255'],
            'thickness' => ['nullable', 'array'],
            'thickness.*' => ['nullable', 'string', 'max:255', 'exists:thicknesses,title'],
            'steel_type' => ['nullable', 'array'],
            'steel_type.*' => ['nullable', 'string', 'max:255', 'exists:steel_types,title'],
            'type' => ['nullable', 'array'],
            'type.*' => ['nullable', 'string', 'max:255', 'exists:types,title'],
            'size' => ['nullable', 'array'],
            'size.*' => ['nullable', 'string', 'max:255', 'exists:sizes,title'],
        ], [
          'one_side.required' => 'Поле "Односторонний" обязательно для заполнения',
          'one_side.string' => 'Поле "Односторонний" должно быть строкой',
          'one_side.max' => 'Поле "Односторонний" должно быть не более 255 символов',
          'set.string' => 'Поле "Комплект" должно быть строкой',
          'set.max' => 'Поле "Комплект" должно быть не более 255 символов',
      ]);

        $price = Price::find($price_id);
        $price->update($request->all());

        return redirect()->route('admin.products.show', $product_slug);
    }

    public function priceDestroy( $product_slug, $price_id)
    {
        $price = Price::find($price_id);
        $price->delete();

        return redirect()->route('admin.products.show', $product_slug);
    }

    // Size =================================================================================================

    public function sizeCreate($product_slug)
    {
        $product = Product::whereSlug($product_slug)->firstOrFail();
        $user = Auth::user();
        return view('admin.size.create', compact('product', 'user'));
    }

    public function sizeStore(Request $request, $product_slug)
    {
        $product = Product::where('slug', $product_slug)->first();
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ],
        [
            'title.required' => 'Поле "Размер" обязательно для заполнения',
            'title.string' => 'Поле "Размер" должно быть строкой',
            'title.max' => 'Поле "Размер" должно быть не более 255 символов',
        ]);
        $data['slug'] = Str::slug($data['title']);
        $product->sizes()->create($data);

        return redirect()->route('admin.products.show', $product_slug)->with('success_size', 'Размер добавлен');
    }

    public function sizeEdit($product_slug, $size_id)
    {
        $product = Product::where('slug', $product_slug)->first();
        $size = $product->sizes->where('id', $size_id)->first();
        $user = Auth::user();
        return view('admin.size.edit', compact('size', 'product', 'user'));
    }

    public function sizeUpdate(Request $request, $product_slug, $size_id)
    {
        $size = Size::find($size_id);
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ],
        [
            'title.required' => 'Поле "Размер" обязательно для заполнения',
            'title.string' => 'Поле "Размер" должно быть строкой',
            'title.max' => 'Поле "Размер" должно быть не более 255 символов',
        ]);
        $data['slug'] = Str::slug($data['title']);
        $size->update($data);

        return redirect()->route('admin.products.show', $product_slug)->with('success_size', 'Размер обновлен');
    }

    public function sizeDestroy( $product_slug, $size_id)
    {
        $size = Size::find($size_id);
        $size->delete();

        return redirect()->route('admin.products.show', $product_slug)->with('success_size', 'Размер удален');
    }

    // Steel Type =================================================================================================

    public function steelTypeCreate($product_slug)
    {
        $product = Product::whereSlug($product_slug)->firstOrFail();
        $user = Auth::user();
        return view('admin.steel_type.create', compact('product', 'user'));
    }

    public function steelTypeStore(Request $request, $product_slug)
    {
        $product = Product::where('slug', $product_slug)->first();
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ],
        [
            'title.required' => 'Поле "Тип стали" обязательно для заполнения',
            'title.string' => 'Поле "Тип стали" должно быть строкой',
            'title.max' => 'Поле "Тип стали" должно быть не более 255 символов',
        ]);
        $data['slug'] = Str::slug($data['title']);
        $product->steel_types()->create($data);

        return redirect()->route('admin.products.show', $product_slug)->with('success_steel_type', 'Тип стали добавлен');
    }

    public function steelTypeEdit($product_slug, $steel_type_id)
    {
        $product = Product::where('slug', $product_slug)->first();
        $steel_type = $product->steel_types->where('id', $steel_type_id)->first();
        $user = Auth::user();

        return view('admin.steel_type.edit', compact('steel_type', 'product', 'user'));
    }

    public function steelTypeUpdate(Request $request, $product_slug, $steel_type_id)
    {
        $steel_type = SteelType::find($steel_type_id);
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ],
        [
            'title.required' => 'Поле "Тип стали" обязательно для заполнения',
            'title.string' => 'Поле "Тип стали" должно быть строкой',
            'title.max' => 'Поле "Тип стали" должно быть не более 255 символов',
        ]);
        $data['slug'] = Str::slug($data['title']);
        $steel_type->update($data);

        return redirect()->route('admin.products.show', $product_slug)->with('success_steel_type', 'Тип стали обновлен');
    }

    public function steelTypeDestroy($product_slug, $steel_type_id)
    {
        $steel_type = SteelType::find($steel_type_id);
        $steel_type->delete();

        return redirect()->route('admin.products.show', $product_slug)->with('success_steel_type', 'Тип стали удален');
    }

    // Thickness =================================================================================================

    public function thicknessCreate($product_slug)
    {
        $product = Product::whereSlug($product_slug)->firstOrFail();
        $user = Auth::user();
        return view('admin.thickness.create', compact('product', 'user'));
    }

    public function thicknessStore(Request $request, $product_slug)
    {
        $product = Product::where('slug', $product_slug)->first();
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ],
        [
            'title.required' => 'Поле "Толщина" обязательно для заполнения',
            'title.string' => 'Поле "Толщина" должно быть строкой',
            'title.max' => 'Поле "Толщина" должно быть не более 255 символов',
        ]);
        $data['slug'] = Str::slug($data['title']);
        $product->thicknesses()->create($data);

        return redirect()->route('admin.products.show', $product_slug)->with('success_thickness', 'Толщина добавлена');
    }

    public function thicknessEdit($product_slug, $thickness_id)
    {
      $product = Product::where('slug', $product_slug)->first();
      $thickness = $product->thicknesses->where('id', $thickness_id)->first();
      $user = Auth::user();
        return view('admin.thickness.edit', compact('thickness', 'product', 'user'));
    }

    public function thicknessUpdate(Request $request, $product_slug, $thickness_id)
    {
        $thickness = Thickness::find($thickness_id);
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ],
        [
            'title.required' => 'Поле "Толщина" обязательно для заполнения',
            'title.string' => 'Поле "Толщина" должно быть строкой',
            'title.max' => 'Поле "Толщина" должно быть не более 255 символов',
        ]);
        $data['slug'] = Str::slug($data['title']);
        $thickness->update($data);

        return redirect()->route('admin.products.show', $product_slug)->with('success_thickness', 'Толщина обновлена');
    }

    public function thicknessDestroy($product_slug, $thickness_id)
    {
        $thickness = Thickness::find($thickness_id);
        $thickness->delete();

        return redirect()->route('admin.products.show', $product_slug)->with('success_thickness', 'Толщина удалена');
    }

    // Type =================================================================================================

    public function typeCreate($product_slug)
    {
        $product = Product::whereSlug($product_slug)->firstOrFail();
        $user = Auth::user();
        return view('admin.type.create', compact('product', 'user'));
    }

    public function typeStore(Request $request, $product_slug)
    {
        $product = Product::where('slug', $product_slug)->first();
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ],
        [
            'title.required' => 'Поле "Тип" обязательно для заполнения',
            'title.string' => 'Поле "Тип" должно быть строкой',
            'title.max' => 'Поле "Тип" должно быть не более 255 символов',
        ]);
        $data['slug'] = Str::slug($data['title']);
        $product->types()->create($data);

        return redirect()->route('admin.products.show', $product_slug)->with('success_type', 'Тип добавлен');
    }

    public function typeEdit($product_slug, $type_id)
    {
        $product = Product::where('slug', $product_slug)->first();
        $type = $product->types->where('id', $type_id)->first();
        $user = Auth::user();
        return view('admin.type.edit', compact('type', 'product', 'user'));
    }

    public function typeUpdate(Request $request, $product_slug, $type_id)
    {
        $type = Type::find($type_id);
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ],
        [
            'title.required' => 'Поле "Тип" обязательно для заполнения',
            'title.string' => 'Поле "Тип" должно быть строкой',
            'title.max' => 'Поле "Тип" должно быть не более 255 символов',
        ]);
        $data['slug'] = Str::slug($data['title']);
        $type->update( $data);

        return redirect()->route('admin.products.show', $product_slug)->with('success_type', 'Тип обновлен');
    }

    public function typeDestroy($product_slug, $type_id)
    {
        $type = Type::find($type_id);
        $type->delete();

        return redirect()->route('admin.products.show', $product_slug)->with('success_type', 'Тип удален');
    }
}
