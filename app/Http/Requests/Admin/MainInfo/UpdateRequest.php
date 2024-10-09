<?php

namespace App\Http\Requests\Admin\MainInfo;

use Illuminate\Foundation\Http\FormRequest;

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
      'company_title' => ['nullable', 'string'],
      'company_details' => ['nullable', 'string'],
      'phone' => ['nullable', 'string'],
      'whats_app' => ['nullable', 'string'],
      'telegram' => ['nullable', 'string'],
      'company_image' => 'nullable|image|max:200000|mimes:jpeg,png,jpg,gif,svg',
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
      'company_title.string' => 'Название компании должно быть строкой',
      'company_details.string' => 'Описание компании должно быть строкой',
      'phone.string' => 'Телефон должен быть строкой',
      'whats_app.string' => 'WhatsApp должен быть строкой',
      'telegram.string' => 'Telegram должен быть строкой',
      'company_image.image' => 'Изображение должно быть изображением',
      'company_image.max' => 'Изображение должно быть меньше 200 КБ',
      'company_image.mimes' => 'Изображение должно быть в формате jpeg,png,jpg,gif,svg'
    ];
  }
}

