<?php

/**
 * RegisterTransaction - Modal de registro rápido de transacciones.
 *
 * @responsable @especialista-frontend
 * @package App\Livewire
 */

namespace App\Livewire;

use App\Services\CategoryService;
use App\Services\TransactionService;
use Livewire\Component;

class RegisterTransaction extends Component
{
    public bool $open = false;
    public string $tab = 'expense';
    public string $description = '';
    public string $amount = '';
    public string $category = '';
    public string $date = '';
    public string $accountId = '';

    /** @var \Illuminate\Database\Eloquent\Collection */
    public $categories;

    /** @var \Illuminate\Database\Eloquent\Collection */
    public $accounts;

    public function mount(): void
    {
        $this->ensureDefaultAccount();
        $this->date = now()->format('Y-m-d');
        $this->loadCategories();
        $this->loadAccounts();
    }

    private function loadAccounts(): void
    {
        $this->accounts = auth()->user()->accounts()->with('currency')->get();

        if (! $this->accountId && $this->accounts->isNotEmpty()) {
            $this->accountId = (string) $this->accounts->first()->id;
        }
    }

    private function ensureDefaultAccount(): void
    {
        $user = auth()->user();
        if ($user->accounts()->count() === 0) {
            $defaultCurrency = \App\Models\Currencies::where('name', 'Dólar')->first();
            if ($defaultCurrency) {
                $user->accounts()->create([
                    'currency_id' => $defaultCurrency->id,
                    'name' => 'Cuenta Principal',
                    'balance' => 0,
                ]);
            }
        }
    }

    public function toggleTab(string $tab): void
    {
        $this->tab = $tab;
        $this->category = '';
        $this->loadCategories();
    }

    public function submit(): void
    {
        $this->validate([
            'description' => ['required', 'string', 'max:500'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'category' => ['required', 'exists:categories,id'],
            'date' => ['required', 'date'],
            'accountId' => ['required', 'exists:accounts,id'],
        ], [
            'description.required' => 'La descripción es obligatoria.',
            'amount.required' => 'El monto es obligatorio.',
            'amount.numeric' => 'El monto debe ser un número válido.',
            'amount.min' => 'El monto debe ser mayor a 0.',
            'category.required' => 'Selecciona una categoría.',
            'category.exists' => 'La categoría seleccionada no es válida.',
            'date.required' => 'La fecha es obligatoria.',
            'date.date' => 'La fecha debe ser válida.',
            'accountId.required' => 'Selecciona una cuenta.',
            'accountId.exists' => 'La cuenta seleccionada no es válida.',
        ]);

        $type = $this->tab === 'income' ? 'income' : 'expense';

        try {
            app(TransactionService::class)->createTransaction([
                'account_id' => (int) $this->accountId,
                'category_id' => (int) $this->category,
                'amount' => (float) $this->amount,
                'type' => $type,
                'description' => $this->description,
                'transaction_date' => $this->date,
            ]);

            $this->dispatch('show-toast', message: 'Transacción registrada exitosamente.', type: 'success');
            $this->dispatch('transaction-saved');

            $this->reset(['description', 'amount', 'category']);
            $this->date = now()->format('Y-m-d');
            $this->open = false;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Error al registrar la transacción.', type: 'error');
        }
    }

    private function loadCategories(): void
    {
        $type = $this->tab === 'income' ? 'income' : 'expense';
        $this->categories = app(CategoryService::class)->getUserCategories(auth()->id(), $type);
    }

    public function render()
    {
        return view('livewire.register-transaction');
    }
}
