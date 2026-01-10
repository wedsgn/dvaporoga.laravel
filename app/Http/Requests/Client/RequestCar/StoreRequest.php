<?php

namespace App\Http\Requests\Client\RequestCar;

use Illuminate\Foundation\Http\FormRequest;

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

        // phone -> E.164 (+7XXXXXXXXXX)
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

        // car_id в int
        if ($this->has('car_id')) {
            $val = $this->input('car_id');
            if (is_string($val)) $val = preg_replace('/\D+/', '', $val);
            $this->merge(['car_id' => is_numeric($val) ? (int)$val : $val]);
        }

        // current_url: trim -> null
        $cur = trim((string) $this->input('current_url', ''));
        $this->merge(['current_url' => $cur === '' ? null : $cur]);

        // form_id: trim -> null
        $form = trim((string) $this->input('form_id', ''));
        $this->merge(['form_id' => $form === '' ? null : $form]);

        // utm: trim -> null
        foreach (['utm_source','utm_medium','utm_campaign','utm_term','utm_content'] as $k) {
            $v = trim((string) $this->input($k, ''));
            $this->merge([$k => $v === '' ? null : $v]);
        }
    }

    public function rules(): array
    {
        return [
            'name'     => ['nullable', 'string', 'min:2', 'max:70'],
            'phone'    => ['bail', 'required', 'string', 'regex:/^\+7\d{10}$/'],

            'form_id'  => ['nullable', 'string', 'max:100'],

            // ключевое: конкретное авто
            'car_id'   => ['bail', 'required', 'integer', 'exists:cars,id'],

            'current_url' => ['nullable', 'string', 'max:2000'],

            // utm необязательные
            'utm_source'   => ['nullable', 'string', 'max:255'],
            'utm_medium'   => ['nullable', 'string', 'max:255'],
            'utm_campaign' => ['nullable', 'string', 'max:255'],
            'utm_term'     => ['nullable', 'string', 'max:255'],
            'utm_content'  => ['nullable', 'string', 'max:255'],

            'policy'   => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'Поле телефон не может быть пустым.',
            'phone.regex'    => 'Телефон должен быть в формате +7XXXXXXXXXX.',

            'car_id.required' => 'Автомобиль не определён.',
            'car_id.exists'   => 'Автомобиль не найден.',

            'policy.accepted' => 'Необходимо согласиться с политикой конфиденциальности.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'   => 'Имя',
            'phone'  => 'Телефон',
            'car_id' => 'Автомобиль',
            'policy' => 'Политика конфиденциальности',
        ];
    }
}
