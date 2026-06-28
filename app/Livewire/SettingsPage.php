<?php

namespace App\Livewire;

use App\Services\SettingService;
use Livewire\Component;
use Livewire\WithFileUploads;

class SettingsPage extends Component
{
    use WithFileUploads;

    public bool $isAdmin = false;
    public string $appName = 'App Finanzas';
    public string $primaryColor = '#6366f1';
    public string $chartIncomeColor = '#22c55e';
    public string $chartExpenseColor = '#f43f5e';
    public $logo = null;
    public ?string $currentLogo = null;

    public function mount(): void
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

    public function setPrimaryColor(string $color): void
    {
        $this->primaryColor = $color;
        $this->dispatch('color-preview', color: $this->primaryColor);
    }

    public function updatedPrimaryColor(): void
    {
        $this->dispatch('color-preview', color: $this->primaryColor);
    }

    public function save(): void
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
        return view('livewire.settings-page');
    }
}
