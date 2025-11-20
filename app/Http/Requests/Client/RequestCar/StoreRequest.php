<?php

namespace App\Http\Requests\Client\RequestCar;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CarModel;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // name: trim -> null
        $name = trim((string) $this->input('name', ''));
        $this->merge(['name' => $name === '' ? null : $name]);

        // phone -> E.164 (+7XXXXXXXXXX) как в твоём файле
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

        // make_id / model_id в int
        foreach (['make_id','model_id'] as $key) {
            if ($this->has($key)) {
                $val = $this->input($key);
                if (is_string($val)) $val = preg_replace('/\D+/', '', $val);
                $this->merge([$key => is_numeric($val) ? (int)$val : $val]);
            }
        }
    }

public function rules(): array
{
    return [
        'name'     => ['nullable','string','min:2','max:70'],
        'phone'    => ['bail','required','string','regex:/^\+7\d{10}$/'],
        'form_id'  => ['nullable','string','max:100'],

        'make_id'  => ['bail','required','integer','exists:car_makes,id'],
        'model_id' => [
            'bail','required','integer',
            function($attr, $value, $fail){
                $makeId = (int) request('make_id');
                $ok = \App\Models\CarModel::where('id', (int)$value)
                    ->where('car_make_id', $makeId)
                    ->exists();
                if (!$ok) $fail('Модель не относится к выбранной марке.');
            }
        ],

        'policy'   => ['accepted'],
    ];
}

public function messages(): array
{
    return [
        'phone.required' => 'Поле телефон не может быть пустым.',
        'phone.regex'    => 'Телефон должен быть в формате +7XXXXXXXXXX.',
        'make_id.required'  => 'Выберите марку.',
        'model_id.required' => 'Выберите модель.',

        'policy.accepted'   => 'Необходимо согласиться с политикой конфиденциальности.',
    ];
}

public function attributes(): array
{
    return [
        'name'     => 'Имя',
        'phone'    => 'Телефон',
        'make_id'  => 'Марка',
        'model_id' => 'Модель',
        'policy'   => 'Политика конфиденциальности',
    ];
}
}
