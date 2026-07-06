<?php

namespace App\Http\Requests\Admin;

use App\Support\UploadValidation;
use Illuminate\Foundation\Http\FormRequest;

class ImportExelRequest extends FormRequest
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
              'file_exel' => UploadValidation::importRules()
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
            'file_exel.required' => 'Поле Файл обязательно для заполнения',
            ...UploadValidation::messages(['file_exel'], 'import', false),
        ];
    }
}
