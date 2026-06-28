<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    {{-- Header --}}
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ __('Bienvenido de nuevo') }}
        </h1>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            {{ __('Inicia sesión en tu cuenta para continuar') }}
        </p>
    </div>

    <x-auth-session-status class="mb-6" :status="session('status')" />

    {{-- Form --}}
    <form wire:submit="login" class="space-y-5">
        <x-input-group
            label="Correo electrónico"
            id="email"
            type="email"
            model="form.email"
            autocomplete="username"
            required
            autofocus
            placeholder="tu@correo.com"
            :error="$errors->get('form.email')"
        >
            <x-slot:icon>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                </svg>
            </x-slot:icon>
        </x-input-group>

        <x-input-group
            label="Contraseña"
            id="password"
            type="password"
            model="form.password"
            autocomplete="current-password"
            required
            placeholder="Ingresa tu contraseña"
            :error="$errors->get('form.password')"
        >
            <x-slot:icon>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
            </x-slot:icon>
        </x-input-group>

        <div class="flex items-center justify-between">
            <x-checkbox-field
                id="remember"
                model="form.remember"
                label="Recordarme"
            />

            @if (Route::has('password.request'))
                <a class="text-sm font-medium transition-colors duration-150 focus:outline-none focus:ring-2 rounded" style="color: var(--color-primary); --tw-ring-color: var(--color-primary); --tw-ring-opacity: 0.2;" href="{{ route('password.request') }}" wire:navigate>
                    {{ __('¿Olvidaste tu contraseña?') }}
                </a>
            @endif
        </div>

        <x-primary-button class="w-full justify-center">
            {{ __('Iniciar sesión') }}
        </x-primary-button>
    </form>

    {{-- Divider --}}
    <div class="relative my-6">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white/80 dark:bg-gray-800/60 text-gray-400 dark:text-gray-500">
                {{ __('o') }}
            </span>
        </div>
    </div>

    {{-- Register link --}}
    <div class="text-center">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('¿No tienes una cuenta?') }}
        </p>
        <a href="{{ route('register') }}" wire:navigate class="mt-2 inline-flex items-center justify-center w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 transition-all duration-150" style="--tw-ring-color: var(--color-primary); --tw-ring-opacity: 0.2;">
            {{ __('Crear una cuenta') }}
        </a>
    </div>
</div>
