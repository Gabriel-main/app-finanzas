<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'newAccountName' => ['required', 'string', 'max:255'],
            'currencyId' => ['required', 'exists:currencies,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'newAccountName.required' => 'El nombre de la cuenta es obligatorio.',
            'newAccountName.max' => 'El nombre no debe exceder los 255 caracteres.',
            'currencyId.required' => 'La moneda es obligatoria.',
            'currencyId.exists' => 'La moneda seleccionada no existe.',
        ];
    }
}
