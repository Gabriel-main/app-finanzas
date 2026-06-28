<?php

/**
 * ProfilePage - Gestión de perfil, cuentas y categorías del usuario.
 *
 * @responsable @especialista-backend
 * @package App\Livewire
 */

namespace App\Livewire;

use App\Models\Categories;
use App\Services\CategoryService;
use App\Services\SettingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProfilePage extends Component
{
    use WithFileUploads;

    public string $activeTab = 'profile';

    // Profile
    public string $name = '';
    public string $email = '';
    public string $currentPassword = '';
    public string $newPassword = '';
    public string $newPasswordConfirmation = '';

    // Accounts
    public bool $showAccountForm = false;
    public string $newAccountName = '';
    public string $currencyId = '';

    // Categories
    public string $categoryTab = 'expense';
    public bool $showCategoryForm = false;
    public string $newCategoryName = '';
    public string $newCategoryColor = '#6366f1';

    // Personalización
    public bool $isAdmin = false;
    public string $appName = 'App Finanzas';
    public string $primaryColor = '#6366f1';
    public string $chartIncomeColor = '#22c55e';
    public string $chartExpenseColor = '#f43f5e';
    public $logo = null;
    public ?string $currentLogo = null;

    /** @var \Illuminate\Database\Eloquent\Collection */
    public $accounts;

    /** @var \Illuminate\Database\Eloquent\Collection */
    public $categories;

    /** @var \Illuminate\Database\Eloquent\Collection */
    public $currencies;

    public function mount(): void
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;

        $this->loadAccounts();
        $this->loadCategories();
        $this->loadCurrencies();
        $this->loadSettings();
    }

    private function loadSettings(): void
    {
        $user = auth()->user();
        $this->isAdmin = $user->isAdmin();

        $service = app(SettingService::class);

        if ($this->isAdmin) {
            $global = $service->getGlobalSettings();
            if ($global) {
                $this->appName = $global->app_name;
                $this->primaryColor = $global->primary_color;
                $this->currentLogo = $global->logo_path;
            }
        } else {
            $merged = $service->getMergedSettings(auth()->id());
            $this->primaryColor = $merged->primary_color;
        }

        $userSettings = $service->getUserSettings(auth()->id());
        $this->chartIncomeColor = $userSettings->chart_income_color;
        $this->chartExpenseColor = $userSettings->chart_expense_color;
    }

    private function loadAccounts(): void
    {
        $this->accounts = auth()->user()->accounts()->with('currency')->get();
    }

    private function loadCategories(): void
    {
        $this->categories = app(CategoryService::class)
            ->getUserCategories(auth()->id(), $this->categoryTab);
    }

    private function loadCurrencies(): void
    {
        $this->currencies = \App\Models\Currencies::all();
    }

    public function setActiveTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    // ─── Profile ───────────────────────────────────────────

    public function saveProfile(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre no debe exceder los 255 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.max' => 'El correo no debe exceder los 255 caracteres.',
        ]);

        DB::transaction(function () {
            auth()->user()->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);
        });

        $this->dispatch('show-toast', message: 'Perfil actualizado exitosamente.', type: 'success');
    }

    public function updatePassword(): void
    {
        $this->validate([
            'currentPassword' => ['required', 'string'],
            'newPassword' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'currentPassword.required' => 'La contraseña actual es obligatoria.',
            'newPassword.required' => 'La nueva contraseña es obligatoria.',
            'newPassword.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'newPassword.confirmed' => 'La confirmación de contraseña no coincide.',
        ]);

        $user = auth()->user();

        if (! Hash::check($this->currentPassword, $user->password)) {
            $this->addError('currentPassword', 'La contraseña actual no es correcta.');
            return;
        }

        DB::transaction(function () use ($user) {
            $user->update([
                'password' => Hash::make($this->newPassword),
            ]);
        });

        $this->reset(['currentPassword', 'newPassword', 'newPasswordConfirmation']);
        $this->dispatch('show-toast', message: 'Contraseña actualizada exitosamente.', type: 'success');
    }

    // ─── Accounts ──────────────────────────────────────────

    public function toggleAccountForm(): void
    {
        $this->showAccountForm = ! $this->showAccountForm;
        $this->newAccountName = '';
        $this->currencyId = '';
        $this->resetValidation();
    }

    public function createAccount(): void
    {
        $this->validate([
            'newAccountName' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\.\-]+$/u'],
            'currencyId' => ['required', 'exists:currencies,id'],
        ], [
            'newAccountName.required' => 'El nombre de la cuenta es obligatorio.',
            'newAccountName.max' => 'El nombre no debe exceder los 255 caracteres.',
            'newAccountName.regex' => 'El nombre solo puede contener letras, espacios, guiones y puntos.',
            'currencyId.required' => 'La moneda es obligatoria.',
            'currencyId.exists' => 'La moneda seleccionada no existe.',
        ]);

        DB::transaction(function () {
            auth()->user()->accounts()->create([
                'currency_id' => (int) $this->currencyId,
                'name' => $this->newAccountName,
                'balance' => 0,
            ]);
        });

        $this->showAccountForm = false;
        $this->newAccountName = '';
        $this->currencyId = '';
        $this->resetValidation();

        $this->loadAccounts();
        $this->dispatch('show-toast', message: 'Cuenta creada exitosamente.', type: 'success');
        $this->dispatch('accounts-updated');
    }

    public function deleteAccount(int $id): void
    {
        $account = auth()->user()->accounts()->find($id);

        if (! $account) {
            return;
        }

        if ($account->transactions()->count() > 0) {
            $this->dispatch('show-toast', message: 'No se puede eliminar una cuenta con transacciones asociadas.', type: 'error');
            return;
        }

        DB::transaction(function () use ($account) {
            $account->delete();
        });

        $this->loadAccounts();
        $this->dispatch('show-toast', message: 'Cuenta eliminada exitosamente.', type: 'success');
        $this->dispatch('accounts-updated');
    }

    // ─── Categories ────────────────────────────────────────

    public function toggleCategoryTab(string $tab): void
    {
        $this->categoryTab = $tab;
        $this->loadCategories();
    }

    public function toggleCategoryForm(): void
    {
        $this->showCategoryForm = ! $this->showCategoryForm;
        $this->newCategoryName = '';
        $this->newCategoryColor = '#6366f1';
        $this->resetValidation();
    }

    public function createCategory(): void
    {
        $type = $this->categoryTab;

        $this->validate([
            'newCategoryName' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\.\-]+$/u'],
            'newCategoryColor' => ['nullable', 'string', 'max:7'],
        ], [
            'newCategoryName.required' => 'El nombre de la categoría es obligatorio.',
            'newCategoryName.max' => 'El nombre no debe exceder los 255 caracteres.',
            'newCategoryName.regex' => 'El nombre solo puede contener letras, espacios, guiones y puntos.',
        ]);

        $exists = Categories::where('user_id', auth()->id())
            ->where('name', $this->newCategoryName)
            ->where('type', $type)
            ->exists();

        if ($exists) {
            $this->addError('newCategoryName', 'Ya existe una categoría con ese nombre para este tipo.');
            return;
        }

        DB::transaction(function () use ($type) {
            app(CategoryService::class)->createCategory(
                auth()->id(),
                [
                    'name' => $this->newCategoryName,
                    'type' => $type,
                    'color' => $this->newCategoryColor,
                ],
            );
        });

        $this->showCategoryForm = false;
        $this->newCategoryName = '';
        $this->newCategoryColor = '#6366f1';
        $this->resetValidation();

        $this->loadCategories();
        $this->dispatch('show-toast', message: 'Categoría creada exitosamente.', type: 'success');
        $this->dispatch('categories-updated');
    }

    public function deleteCategory(int $id): void
    {
        $category = Categories::find($id);

        if (! $category || $category->user_id !== auth()->id()) {
            return;
        }

        if ($category->transactions()->count() > 0) {
            $this->dispatch('show-toast', message: 'No se puede eliminar una categoría con transacciones asociadas.', type: 'error');
            return;
        }

        DB::transaction(function () use ($id) {
            app(CategoryService::class)->deleteCategory(auth()->id(), $id);
        });

        $this->loadCategories();
        $this->dispatch('show-toast', message: 'Categoría eliminada exitosamente.', type: 'success');
        $this->dispatch('categories-updated');
    }

    // ─── Personalización ────────────────────────────────────

    public function setPrimaryColor(string $color): void
    {
        $this->primaryColor = $color;
        $this->dispatch('color-preview', color: $this->primaryColor);
    }

    public function updatedPrimaryColor(): void
    {
        $this->dispatch('color-preview', color: $this->primaryColor);
    }

    public function saveSettings(): void
    {
        $service = app(SettingService::class);

        if ($this->isAdmin) {
            $this->validate([
                'appName' => ['required', 'string', 'max:255'],
                'primaryColor' => ['required', 'string', 'max:7'],
                'logo' => ['nullable', 'image', 'max:2048'],
                'chartIncomeColor' => ['required', 'string', 'max:7'],
                'chartExpenseColor' => ['required', 'string', 'max:7'],
            ]);

            $globalData = [
                'app_name' => $this->appName,
                'primary_color' => $this->primaryColor,
            ];

            if ($this->logo) {
                $globalData['logo_path'] = $this->logo->store('logos', 'public');
            }

            $service->updateGlobalSettings($globalData);
        } else {
            $this->validate([
                'primaryColor' => ['required', 'string', 'max:7'],
                'chartIncomeColor' => ['required', 'string', 'max:7'],
                'chartExpenseColor' => ['required', 'string', 'max:7'],
            ]);
        }

        $service->updateSettings(auth()->id(), [
            'primary_color' => $this->primaryColor,
            'chart_income_color' => $this->chartIncomeColor,
            'chart_expense_color' => $this->chartExpenseColor,
        ]);

        $this->dispatch('show-toast', message: 'Configuración guardada exitosamente.', type: 'success');
        $this->dispatch('settings-updated');
    }

    public function removeLogo(): void
    {
        if (!$this->isAdmin) {
            return;
        }

        app(SettingService::class)->updateGlobalSettings([
            'logo_path' => null,
        ]);
        $this->currentLogo = null;
        $this->logo = null;
        $this->dispatch('show-toast', message: 'Logo eliminado.', type: 'success');
        $this->dispatch('settings-updated');
    }

    public function render()
    {
        return view('livewire.profile-page');
    }
}
