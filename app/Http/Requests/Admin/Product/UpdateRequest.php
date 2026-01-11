<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'title' => ['required', 'max:70'],



      'image' => 'nullable|image|max:200000|mimes:jpeg,png,jpg,gif,svg',
      'image_mob' => 'nullable|image|max:200000|mimes:jpeg,png,jpg,gif,svg',
      'description' => ['nullable'],
      'car_ids' => ['nullable', 'array'],
      'car_ids.*' => ['integer', 'exists:cars,id'],
      'car_ids_json' => ['nullable', 'string'],
      'price' => ['nullable', 'integer', 'min:0'],
      'discount_percentage' => ['nullable', 'integer', 'min:0', 'max:100'],
      'price_old' => ['nullable', 'integer', 'min:0'],
    ];
  }

  public function messages(): array
  {
    return [
      'title.required' => 'Поле "Название" обязательно для заполнения',
      'title.max' => 'Поле "Название" должно быть не более 70 символов',


      'image.max' => 'Размер изображения не должен превышать 200 Мбайт',
      'image_mob.max' => 'Размер изображения (мобильная) не должен превышать 200 Мбайт',
    ];
  }
}
