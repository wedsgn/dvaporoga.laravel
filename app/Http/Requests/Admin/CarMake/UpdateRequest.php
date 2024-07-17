<?php

namespace App\Http\Requests\Admin\CarMake;

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
            'title' => ['required', 'max:70', Rule::unique('car_makes')->ignore($this->old_title, 'title')],
            'image' => 'nullable|image|max:200000|mimes:jpeg,png,jpg,gif,svg',
            'image_mob' => 'nullable|image|max:200000|mimes:jpeg,png,jpg,gif,svg',
            'description'  => ['nullable'],
        ];
    }
}

