<?php

namespace App\Http\Requests\Admin\MainInfo;

use App\Support\UploadValidation;
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
      'vk' => ['nullable', 'string'],
      'max' => ['nullable', 'string'],
      'phone_clients' => ['nullable', 'string'],
      'company_image' => UploadValidation::imageRules(),
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
      ...UploadValidation::messages(['company_image']),
      'vk.string' => 'VK должен быть строкой',
      'max.string' => 'MAX должен быть строкой',
      'phone_clients.string' => 'Телефон клиентов должен быть строкой',
    ];
  }
}
