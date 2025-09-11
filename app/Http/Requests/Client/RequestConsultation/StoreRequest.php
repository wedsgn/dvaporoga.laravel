<?php

namespace App\Http\Requests\Client\RequestConsultation;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;
class StoreRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Нормализация перед валидацией:
   * - phone: оставляем только цифры, приводим к +7XXXXXXXXXX
   * - name: trim + схлопываем пробелы + вырезаем тэги
   */
  protected function prepareForValidation(): void
  {
    // ---- phone
    $raw = (string) $this->input('phone', '');
    $digits = preg_replace('/\D+/', '', $raw ?? '');

    if ($digits !== '') {
      if (strlen($digits) === 11 && str_starts_with($digits, '8')) {
        $digits = '7' . substr($digits, 1);
      }
      if (strlen($digits) === 10) {
        $digits = '7' . $digits;
      }
      if (strlen($digits) === 11 && str_starts_with($digits, '7')) {
        $this->merge(['phone' => '+' . $digits]);
      }
    }

    // ---- name
    $name = (string) $this->input('name', '');
    $name = trim($name);
    $name = preg_replace('/\s+/u', ' ', $name);
    $name = strip_tags($name);

    $this->merge(['name' => $name]);
  }

  public function rules(): array
  {
    return [
      'name' => [
        'nullable',
        'string',
        'min:2',
        'max:70',
        Rule::requiredIf(fn () => $this->input('form_id') !== 'footer-form'),

      ],

      'phone'   => ['bail', 'required', 'string', 'regex:/^\+7\d{10}$/'],
      'form_id' => ['required', 'string', 'max:100'],
    ];
  }

  public function messages(): array
  {
    return [
      'name.required'   => 'Поле имя не может быть пустым.',
      'name.min'        => 'Имя должно быть не короче :min символов.',
      'name.max'        => 'Имя не может быть длиннее :max символов.',

      'phone.required'  => 'Поле телефон не может быть пустым.',
      'phone.regex'     => 'Телефон должен быть в формате +7(XXX)XXXXXXX.',

      'form_id.required' => 'Поле форма не может быть пустым.',
      'form_id.max'     => 'Поле форма слишком длинное.',
    ];
  }

  public function attributes(): array
  {
    return [
      'name'    => 'Имя',
      'phone'   => 'Телефон',
      'form_id' => 'Форма',
    ];
  }
}
