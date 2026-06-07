<?php

namespace App\Http\Requests;

use App\Models\Categories;
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
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:income,expense'],
            'color' => ['nullable', 'string', 'max:7'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->any()) {
                return;
            }

            $exists = Categories::where('user_id', auth()->id())
                ->where('name', $this->name)
                ->where('type', $this->type)
                ->exists();

            if ($exists) {
                $validator->errors()->add(
                    'name',
                    'Ya existe una categoría con ese nombre para este tipo.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la categoría es obligatorio.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'type.required' => 'El tipo de categoría es obligatorio.',
            'type.in' => 'El tipo debe ser "income" o "expense".',
            'color.max' => 'El color no puede tener más de 7 caracteres.',
        ];
    }
}
