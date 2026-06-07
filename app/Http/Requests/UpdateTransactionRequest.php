<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_id' => ['required', 'exists:accounts,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'type' => ['required', 'in:income,expense'],
            'description' => ['nullable', 'string', 'max:500'],
            'transaction_date' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'account_id.required' => 'La cuenta es obligatoria.',
            'account_id.exists' => 'La cuenta seleccionada no existe.',
            'category_id.exists' => 'La categoría seleccionada no existe.',
            'amount.required' => 'El monto es obligatorio.',
            'amount.numeric' => 'El monto debe ser un número.',
            'amount.min' => 'El monto debe ser mayor a 0.',
            'type.required' => 'El tipo de transacción es obligatorio.',
            'type.in' => 'El tipo debe ser "income" o "expense".',
            'transaction_date.required' => 'La fecha es obligatoria.',
            'transaction_date.date' => 'La fecha debe ser válida.',
        ];
    }
}
