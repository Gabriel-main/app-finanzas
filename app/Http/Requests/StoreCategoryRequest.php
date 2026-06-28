<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'newCategoryName' => ['required', 'string', 'max:255'],
            'newCategoryType' => ['required', 'in:income,expense'],
            'newCategoryColor' => ['nullable', 'string', 'max:7'],
        ];
    }

    public function messages(): array
    {
        return [
            'newCategoryName.required' => 'El nombre de la categoría es obligatorio.',
            'newCategoryName.max' => 'El nombre no debe exceder los 255 caracteres.',
            'newCategoryType.required' => 'El tipo de categoría es obligatorio.',
            'newCategoryType.in' => 'El tipo debe ser "income" o "expense".',
        ];
    }
}
