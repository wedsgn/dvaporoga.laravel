<?php

namespace App\Http\Requests\Admin\Block;

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
      'title' => ['nullable', 'string', 'max:255'],

      'selected_products' => ['nullable', 'array'],
      'selected_products.*' => ['integer', 'exists:products,id'],
      'ordered_products' => ['nullable', 'array'],
      'ordered_products.*' => ['integer', 'exists:products,id'],

      'keep_images' => ['nullable', 'array'],
      'keep_images.*' => ['string'],

      'new_images' => ['nullable', 'array'],
      'new_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],

      'keep_items' => ['nullable', 'array'],
      'keep_items.*' => ['integer'],

      'new_items' => ['nullable', 'array'],
      'new_items.*.before' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
      'new_items.*.after' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],

    ];
  }
}
