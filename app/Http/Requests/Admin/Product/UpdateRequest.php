<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'title' => ['required', 'max:70', Rule::unique('products')->ignore($this->old_title, 'title')],
      'price_one_side' => ['nullable', 'string'],
      'price_set' => ['nullable', 'string'],
      'metal_thickness' => ['nullable', 'string'],
      'size' => ['nullable', 'string'],
      'material' => ['required', 'string'],
      'side' => ['nullable', 'string'],
      'image' => 'nullable|image|max:200000|mimes:jpeg,png,jpg,gif,svg',
      'image_mob' => 'nullable|image|max:200000|mimes:jpeg,png,jpg,gif,svg',
      'description'  => ['nullable'],
      'cars' => 'nullable|array',
      'cars.*' => 'nullable|string|exists:cars,title'
    ];
  }

  /**
   * Get the error messages for the defined validation rules.
   *
   * @return array<string, string>
   */
  public function messages(): array
  {
    return [
      'title.required' => 'Поле "Название" обязательно для заполнения',
      'title.max' => 'Поле "Название" должно быть не более 70 символов',
      'title.unique' => 'Продукт с таким названием уже существует',
      'price_one_side.string' => 'Цена (односторонняя) должна быть строкой',
      'price_set.string' => 'Цена (набор) должна быть строкой',
      'metal_thickness.string' => 'Толщина металла должна быть строкой',
      'size.string' => 'Размер должен быть строкой',
      'material.string' => 'Материал должен быть строкой',
      'side.string' => 'Сторона должна быть строкой',
      'image.max' => 'Размер изображения не должен превышать 200 Мбайт',
      'image_mob.max' => 'Размер изображения (мобильная) не должен превышать 200 Мбайт',
      'cars.*.exists' => 'Машина с таким названием не существует'
    ];
  }
}
