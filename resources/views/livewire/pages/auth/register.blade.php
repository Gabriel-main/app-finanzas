<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    {{-- Header --}}
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ __('Crea tu cuenta') }}
        </h1>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            {{ __('Comienza a gestionar tus finanzas hoy') }}
        </p>
    </div>

    {{-- Form --}}
    <form wire:submit="register" class="space-y-5">
        <x-input-group
            label="Nombre"
            id="name"
            type="text"
            model="name"
            autocomplete="name"
            required
            autofocus
            placeholder="Tu nombre completo"
            :error="$errors->get('name')"
        >
            <x-slot:icon>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
            </x-slot:icon>
        </x-input-group>

        <x-input-group
            label="Correo electrónico"
            id="email"
            type="email"
            model="email"
            autocomplete="username"
            required
            placeholder="tu@correo.com"
            :error="$errors->get('email')"
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
            model="password"
            autocomplete="new-password"
            required
            placeholder="Crea una contraseña segura"
            :error="$errors->get('password')"
        >
            <x-slot:icon>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
            </x-slot:icon>
        </x-input-group>

        <x-input-group
            label="Confirmar contraseña"
            id="password_confirmation"
            type="password"
            model="password_confirmation"
            autocomplete="new-password"
            required
            placeholder="Repite tu contraseña"
            :error="$errors->get('password_confirmation')"
        >
            <x-slot:icon>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                </svg>
            </x-slot:icon>
        </x-input-group>

        <x-primary-button class="w-full justify-center">
            {{ __('Crear cuenta') }}
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

    {{-- Login link --}}
    <div class="text-center">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('¿Ya tienes una cuenta?') }}
        </p>
        <a href="{{ route('login') }}" wire:navigate class="mt-2 inline-flex items-center justify-center w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 transition-all duration-150" style="--tw-ring-color: var(--color-primary); --tw-ring-opacity: 0.2;">
            {{ __('Iniciar sesión') }}
        </a>
    </div>
</div>
