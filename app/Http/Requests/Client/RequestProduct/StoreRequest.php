<?php

namespace App\Http\Requests\Client\RequestProduct;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Нормализуем входные данные перед валидацией.
     */
    protected function prepareForValidation(): void
    {
        // Имя — подрежем пробелы
        $name = trim((string) $this->input('name', ''));
        $this->merge(['name' => $name]);

        // Телефон -> E.164 RU (+7XXXXXXXXXX)
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

        // Если data пришла массивом — закодируем в JSON-строку
        $data = $this->input('data');
        if (is_array($data)) {
            $this->merge(['data' => json_encode($data, JSON_UNESCAPED_UNICODE)]);
        }

        // total_price приведём к целому числу (убираем пробелы/«руб» и пр.)
        if ($this->has('total_price')) {
            $priceDigits = preg_replace('/[^\d]/', '', (string) $this->input('total_price'));
            $this->merge(['total_price' => $priceDigits !== '' ? (int) $priceDigits : null]);
        }
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'min:2', 'max:70'],
            'phone'       => ['bail', 'required', 'string', 'regex:/^\+7\d{10}$/'],
            // data обязана быть корректным JSON и не пустым массивом
            'data'        => ['required', 'string', 'json', 'not_in:[]'],
            'form_id'     => ['nullable', 'string', 'max:100'],
            'total_price' => ['nullable', 'integer', 'min:0'],
            'car'         => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Поле имя не может быть пустым.',
            'name.min'      => 'Имя должно быть не короче :min символов.',
            'name.max'      => 'Имя не может быть длиннее :max символов.',

            'phone.required' => 'Поле телефон не может быть пустым.',
            'phone.regex'    => 'Телефон должен быть в формате +7(XXX)XXXXXXX.',

            'data.required' => 'Корзина пуста: добавьте хотя бы одну запчасть.',
            'data.json'     => 'Поле «Данные» должно быть корректным JSON.',
            'data.not_in'   => 'Корзина пуста: добавьте хотя бы одну запчасть.',

            'form_id.max' => 'Поле «Форма» слишком длинное.',

            'total_price.integer' => 'Сумма должна быть числом.',
            'total_price.min'     => 'Сумма не может быть отрицательной.',

            'car.max' => 'Поле «Автомобиль» слишком длинное.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'        => 'Имя',
            'phone'       => 'Телефон',
            'data'        => 'Данные',
            'form_id'     => 'Форма',
            'total_price' => 'Сумма',
            'car'         => 'Автомобиль',
        ];
    }
}
