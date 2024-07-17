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
      'price_one_side' => ['nullable', 'string'],
      'price_set' => ['nullable', 'string'],
      'metal_thickness' => ['nullable', 'string'],
      'size' => ['nullable', 'string'],
      'image' => 'nullable|image|max:200000|mimes:jpeg,png,jpg,gif,svg',
      'image_mob' => 'nullable|image|max:200000|mimes:jpeg,png,jpg,gif,svg',
      'description'  => ['nullable'],
      'cars' => 'nullable|array',
      'cars.*' => 'nullable|string|exists:cars,title'
    ];
  }
}
