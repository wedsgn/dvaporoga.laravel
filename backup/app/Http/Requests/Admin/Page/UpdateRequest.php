<?php

namespace App\Http\Requests\Admin\Page;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'title' => ['required', 'max:140', Rule::unique('pages')->ignore($this->old_title, 'title')],
            'description'  => ['nullable'],
            'meta_title' => ['nullable', 'max:140'],
            'meta_description' => ['nullable', 'max:280'],
            'meta_keywords' => ['nullable'],
            'og_title' => ['nullable', 'max:140'],
            'og_description' => ['nullable', 'max:280'],
            'og_url' => ['nullable', 'max:280']
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
            'title.required' => 'Поле заголовок не может быть пустым',
            'title.max' => 'Поле заголовок не может быть больше 140 символов',
            'title.unique' => 'Поле заголовок должно быть уникальным',
            'description.required' => 'Поле описание не может быть пустым',
            'meta_title.max' => 'Поле meta_title не может быть больше 140 символов',
            'meta_description.max' => 'Поле meta_description не может быть больше 280 символов',
            'og_title.max' => 'Поле og_title не может быть больше 140 символов',
            'og_description.max' => 'Поле og_description не может быть больше 280 символов',
            'og_url.max' => 'Поле og_url не может быть больше 280 символов'
        ];
    }
}

