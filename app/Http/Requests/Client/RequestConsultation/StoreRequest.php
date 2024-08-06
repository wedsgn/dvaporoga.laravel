<?php

namespace App\Http\Requests\Client\RequestConsultation;

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
            'name' => ['nullable', 'max:70'],
            'phone' => ['required', 'regex:/^\+7\s*\([0-9]{3}\)\s*[0-9]{3}-[0-9]{2}-[0-9]{2}$/'],
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
            'name.max' => 'Поле имя не может быть больше 70 символов',
            'phone.required' => 'Поле телефон не может быть пустым',
            'phone.regex' => 'Поле телефон должно быть в формате +7 (999) 999-99-99',
            'form_id.required' => 'Поле форма не может быть пустым',
            'form_id.integer' => 'Поле форма должно быть числом',
        ];
    }
}

