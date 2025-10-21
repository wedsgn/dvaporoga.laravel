<?php

namespace App\Http\Requests\Admin\CarModel;

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
            'title' => ['required', 'max:70', 'unique:car_models,title'],
            'image' => 'nullable|image|max:200000|mimes:jpeg,png,jpg,gif,svg',
            'image_mob' => 'nullable|image|max:200000|mimes:jpeg,png,jpg,gif,svg',
            'description'  => ['required'],
            'car_make_id' => 'required',
            'meta_title' => ['nullable', 'max:70'],
            'meta_description' => ['nullable', 'max:160'],
            'meta_keywords' => ['nullable', 'max:160'],
            'og_title' => ['nullable', 'max:70'],
            'og_description' => ['nullable', 'max:160'],
            'og_url' => ['nullable', 'max:160']
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
            'title.required' => 'Поле "Название" обязательно для заполнения',
            'title.max' => 'Поле "Название" должно быть не более 70 символов',
            'title.unique' => 'Модель автомобиля с таким названием уже существует',
            'image.max' => 'Размер изображения не должен превышать 200 Мбайт',
            'image_mob.max' => 'Размер изображения не должен превышать 200 Мбайт',
            'car_make_id.required' => 'Поле "Марка" обязательно для заполнения',
            'description.required' => 'Поле "Описание" обязательно для заполнения',
            'meta_title.max' => 'Поле meta_title не может быть больше 70 символов',
            'meta_description.max' => 'Поле meta_description не может быть больше 160 символов',
            'meta_keywords.max' => 'Поле meta_keywords не может быть больше 160 символов',
            'og_title.max' => 'Поле og_title не может быть больше 70 символов',
            'og_description.max' => 'Поле og_description не может быть больше 160 символов',
            'og_url.max' => 'Поле og_url не может быть больше 160 символов'
        ];
    }
}

