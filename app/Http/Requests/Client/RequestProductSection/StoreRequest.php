<?php

namespace App\Http\Requests\Client\RequestProductSection;

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
            'phone' => ['required'],
            'form_id' => ['nullable', 'string'],
            'product_id' => ['required'],
            'price_id' => ['required'],
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
            'product_id.required' => 'Поле продукт не может быть пустым',
            'price_id.required' => 'Поле цена не может быть пустым',];
    }
}

