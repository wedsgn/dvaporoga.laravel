<?php

namespace App\Http\Requests\Admin\Blog;

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
            'title' => ['required', 'max:70', Rule::unique('blogs')->ignore($this->old_title, 'title')],
            'description'  => ['required'],
            'description_short'  => ['required'],
            'image' => ['nullable', 'image', 'max:200000', 'mimes:jpeg,png,jpg,gif,svg'],
            'image_mob' => ['nullable', 'image', 'max:200000', 'mimes:jpeg,png,jpg,gif,svg'],
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
            'title.max' => 'Поле заголовок не может быть больше 70 символов',
            'title.unique' => 'Поле заголовок должно быть уникальным',
            'description.required' => 'Поле описание не может быть пустым',
            'description_short.required' => 'Поле краткое описание не может быть пустым',
            'image.max' => 'Размер изображения не должен превышать 200 Мбайт',
            'image_mob.max' => 'Размер изображения (мобильная) не должен превышать 200 Мбайт',
            'image.image' => 'Изображение должно быть файлом изображения',
            'image_mob.image' => 'Изображение (мобильная) должно быть файлом изображения',
            'image.mimes' => 'Формат изображения не поддерживается',
            'image_mob.mimes' => 'Формат изображения (мобильная) не поддерживается',
        ];
    }
}

