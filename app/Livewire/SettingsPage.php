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
    public $logo = null;
    public ?string $currentLogo = null;

    public function mount(): void
    {
        $user = auth()->user();
        $this->isAdmin = $user->isAdmin();

        $settings = app(SettingService::class)->getUserSettings(auth()->id());
        $this->appName = $settings->app_name;
        $this->primaryColor = $settings->primary_color;
        $this->currentLogo = $settings->logo_path;
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
        if ($this->isAdmin) {
            $this->validate([
                'appName' => ['required', 'string', 'max:255'],
                'primaryColor' => ['required', 'string', 'max:7'],
                'logo' => ['nullable', 'image', 'max:2048'],
            ]);
        } else {
            $this->validate([
                'primaryColor' => ['required', 'string', 'max:7'],
            ]);
        }

        $data = [
            'primary_color' => $this->primaryColor,
        ];

        if ($this->isAdmin) {
            $data['app_name'] = $this->appName;

            if ($this->logo) {
                $data['logo_path'] = $this->logo->store('logos', 'public');
            }
        }

        app(SettingService::class)->updateSettings(auth()->id(), $data);

        $this->dispatch('show-toast', message: 'Configuración guardada exitosamente.', type: 'success');
        $this->dispatch('settings-updated');
    }

    public function removeLogo(): void
    {
        if (!$this->isAdmin) {
            return;
        }

        app(SettingService::class)->updateSettings(auth()->id(), [
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
