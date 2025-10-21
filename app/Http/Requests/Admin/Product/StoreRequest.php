<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
      'title' => ['required', 'max:70', 'unique:products,title'],
      'image' => 'nullable|image|max:200000|mimes:jpeg,png,jpg,gif,svg',
      'image_mob' => 'nullable|image|max:200000|mimes:jpeg,png,jpg,gif,svg',
      'description'  => ['nullable'],
      'cars' => 'nullable|array',
      'cars.*' => 'nullable|string|exists:cars,title',
      // 'meta_title.max' => 'Поле meta_title не может быть больше 70 символов',
      // 'meta_description.max' => 'Поле meta_description не может быть больше 160 символов',
      // 'meta_keywords.max' => 'Поле meta_keywords не может быть больше 160 символов',
      // 'og_title.max' => 'Поле og_title не может быть больше 70 символов',
      // 'og_description.max' => 'Поле og_description не может быть больше 160 символов',
      // 'og_url.max' => 'Поле og_url не может быть больше 160 символов'

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
      'image.max' => 'Размер изображения не должен превышать 200 Мбайт',
      'image_mob.max' => 'Размер изображения (мобильная) не должен превышать 200 Мбайт',
      'cars.*.exists' => 'Машина с таким названием не существует',
      // 'meta_title.max' => 'Поле meta_title не может быть больше 70 символов',
      // 'meta_description.max' => 'Поле meta_description не может быть больше 160 символов',
      // 'meta_keywords.max' => 'Поле meta_keywords не может быть больше 160 символов',
      // 'og_title.max' => 'Поле og_title не может быть больше 70 символов',
      // 'og_description.max' => 'Поле og_description не может быть больше 160 символов',
      // 'og_url.max' => 'Поле og_url не может быть больше 160 символов'
    ];
  }
}

