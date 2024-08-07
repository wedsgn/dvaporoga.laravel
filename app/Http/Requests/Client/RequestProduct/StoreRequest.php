<?php

namespace App\Http\Requests\Client\RequestProduct;

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
            'name' => ['required', 'max:70'],
            'phone' => ['required'],
            'data' => ['required'],
            'form_id' => ['nullable', 'string'],
            'total_price' => ['nullable', 'string'],
            'car' => ['nullable', 'string'],
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
            'name.max' => 'Поле "Имя" не может быть длиннее 70 символов.',
            'phone.required' => 'Поле "Телефон" не может быть пустым.',
            'form_id.required' => 'Поле "Форма" не может быть пустым.',
            'form_id.integer' => 'Поле "Форма" должно быть числом.',
            'data.required' => 'Поле "Данные" не может быть пустым.',
        ];
    }
}

