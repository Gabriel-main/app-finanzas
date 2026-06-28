<?php

namespace App\Livewire;

use App\Http\Requests\StoreAccountRequest;
use App\Models\Categories;
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

    public bool $showCategoryForm = false;
    public string $newCategoryName = '';
    public string $newCategoryColor = '#6366f1';

    public bool $showAccountForm = false;
    public string $newAccountName = '';
    public string $currencyId = '';

    /** @var \Illuminate\Database\Eloquent\Collection */
    public $categories;

    /** @var \Illuminate\Database\Eloquent\Collection */
    public $accounts;

    /** @var \Illuminate\Database\Eloquent\Collection */
    public $currencies;

    public function mount(): void
    {
        $this->ensureDefaultAccount();
        $this->date = now()->format('Y-m-d');
        $this->loadCategories();
        $this->loadAccounts();
        $this->loadCurrencies();
    }

    private function loadAccounts(): void
    {
        $this->accounts = auth()->user()->accounts()->with('currency')->get();

        if (! $this->accountId && $this->accounts->isNotEmpty()) {
            $this->accountId = (string) $this->accounts->first()->id;
        }
    }

    private function loadCurrencies(): void
    {
        $this->currencies = \App\Models\Currencies::all();
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

    public function toggleCategoryForm(): void
    {
        $this->showCategoryForm = ! $this->showCategoryForm;
        $this->newCategoryName = '';
        $this->newCategoryColor = '#6366f1';
        $this->resetValidation();
    }

    public function toggleAccountForm(): void
    {
        $this->showAccountForm = ! $this->showAccountForm;
        $this->newAccountName = '';
        $this->currencyId = '';
        $this->resetValidation();
    }

    public function createAccount(): void
    {
        $validated = $this->validate(new StoreAccountRequest());

        $account = auth()->user()->accounts()->create([
            'currency_id' => (int) $validated['currencyId'],
            'name' => $validated['newAccountName'],
            'balance' => 0,
        ]);

        $this->accountId = (string) $account->id;
        $this->showAccountForm = false;
        $this->newAccountName = '';
        $this->currencyId = '';
        $this->resetValidation();

        $this->loadAccounts();
        $this->dispatch('accounts-updated');
    }

    public function createCategory(): void
    {
        $type = $this->tab === 'income' ? 'income' : 'expense';

        $validated = $this->validate([
            'newCategoryName' => ['required', 'string', 'max:255', function ($attribute, $value, $fail) use ($type) {
                $exists = Categories::where('user_id', auth()->id())
                    ->where('name', $value)
                    ->where('type', $type)
                    ->exists();
                if ($exists) {
                    $fail('Ya existe una categoría con ese nombre para este tipo.');
                }
            }],
            'newCategoryColor' => ['nullable', 'string', 'max:7'],
        ]);

        $newCategory = app(CategoryService::class)->createCategory(
            auth()->id(),
            [
                'name' => $validated['newCategoryName'],
                'type' => $type,
                'color' => $validated['newCategoryColor'] ?? $this->newCategoryColor,
            ],
        );

        $this->category = (string) $newCategory->id;
        $this->showCategoryForm = false;
        $this->newCategoryName = '';
        $this->newCategoryColor = '#6366f1';
        $this->resetValidation();

        $this->loadCategories();
        $this->dispatch('categories-updated', categories: $this->categories->map(fn ($c) => ['id' => $c->id, 'name' => $c->name, 'color' => $c->color])->values()->all());
    }

    public function submit(): void
    {
        $this->validate([
            'description' => ['required', 'string', 'max:500'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'category' => ['required', 'exists:categories,id'],
            'date' => ['required', 'date'],
            'accountId' => ['required', 'exists:accounts,id'],
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
