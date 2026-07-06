<?php

namespace App\Http\Requests\Admin\Block;

use App\Support\UploadValidation;
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
      'new_images.*' => UploadValidation::imageRules('nullable'),

      'keep_items' => ['nullable', 'array'],
      'keep_items.*' => ['integer'],

      'new_items' => ['nullable', 'array'],
      'new_items.*.before' => UploadValidation::imageRules('nullable'),
      'new_items.*.after' => UploadValidation::imageRules('nullable'),

    ];
  }

  public function messages(): array
  {
    return UploadValidation::messages([
      'new_images.*',
      'new_items.*.before',
      'new_items.*.after',
    ]);
  }
}
