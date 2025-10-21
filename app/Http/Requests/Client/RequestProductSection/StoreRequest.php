<?php

namespace App\Http\Requests\Client\RequestProductSection;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Нормализация входных данных перед валидацией.
     */
    protected function prepareForValidation(): void
    {
        // name: trim, пустую строку -> null
        $name = trim((string) $this->input('name', ''));
        $this->merge(['name' => $name === '' ? null : $name]);

        // phone -> E.164 RU (+7XXXXXXXXXX)
        $raw    = (string) $this->input('phone', '');
        $digits = preg_replace('/\D+/', '', $raw);
        if ($digits !== '') {
            if (strlen($digits) === 11 && $digits[0] === '8') {
                $digits = '7' . substr($digits, 1);
            }
            if (strlen($digits) === 10) {
                $digits = '7' . $digits;
            }
            if (strlen($digits) === 11 && $digits[0] === '7') {
                $this->merge(['phone' => '+' . $digits]);
            }
        }

        // product_price: оставляем только цифры -> int
        if ($this->has('product_price')) {
            $priceDigits = preg_replace('/[^\d]/', '', (string) $this->input('product_price'));
            $this->merge(['product_price' => $priceDigits !== '' ? (int) $priceDigits : null]);
        }

        // product_id / price_id: в int (если пришли строками)
        foreach (['product_id', 'price_id'] as $key) {
            if ($this->has($key)) {
                $val = $this->input($key);
                // иногда прилетает строка с цифрами — приведём
                if (is_string($val)) {
                    $val = preg_replace('/\D+/', '', $val);
                }
                $this->merge([$key => is_numeric($val) ? (int) $val : $val]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'name'          => ['bail', 'required', 'string', 'min:2', 'max:70'],
            'phone'         => ['bail', 'required', 'string', 'regex:/^\+7\d{10}$/'],
            'form_id'       => ['nullable', 'string', 'max:100'],

            'product_id'    => ['required', 'integer'],
            'price_id'      => ['required', 'integer'],
            'product_price' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.min'            => 'Имя должно быть не короче :min символов.',
            'name.max'            => 'Имя не может быть длиннее :max символов.',

            'phone.required'      => 'Поле телефон не может быть пустым.',
            'phone.regex'         => 'Телефон должен быть в формате +7(XXX)XXXXXXX.',

            'form_id.max'         => 'Поле форма слишком длинное.',

            'product_id.required' => 'Поле продукт не может быть пустым.',
            'product_id.integer'  => 'Поле продукт должно быть числом.',

            'price_id.required'   => 'Поле цена не может быть пустым.',
            'price_id.integer'    => 'Поле цена должно быть числом.',

            'product_price.integer' => 'Цена должна быть числом.',
            'product_price.min'     => 'Цена не может быть отрицательной.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'          => 'Имя',
            'phone'         => 'Телефон',
            'form_id'       => 'Форма',
            'product_id'    => 'Продукт',
            'price_id'      => 'Цена',
            'product_price' => 'Цена',
        ];
    }
}
