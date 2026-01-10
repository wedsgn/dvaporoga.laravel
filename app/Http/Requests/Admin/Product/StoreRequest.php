<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'title' => ['required', 'max:70'],

      'car_id' => ['required', 'integer', 'exists:cars,id'],

      'image' => 'nullable|image|max:200000|mimes:jpeg,png,jpg,gif,svg',
      'image_mob' => 'nullable|image|max:200000|mimes:jpeg,png,jpg,gif,svg',
      'description' => ['nullable'],

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

      'car_id.required' => 'Выберите автомобиль',
      'car_id.integer' => 'Некорректная машина',
      'car_id.exists' => 'Выбранная машина не существует',

      'image.max' => 'Размер изображения не должен превышать 200 Мбайт',
      'image_mob.max' => 'Размер изображения (мобильная) не должен превышать 200 Мбайт',
    ];
  }
}
