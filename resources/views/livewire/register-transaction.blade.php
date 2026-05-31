<?php

use Livewire\Volt\Component;

new class extends Component
{
    public bool $open = false;
    public string $tab = 'expense';
    public string $description = '';
    public string $amount = '';
    public string $category = '';
    public string $date = '';

    public function mount(): void
    {
        $this->date = now()->format('Y-m-d');
    }

    public function toggleTab(string $tab): void
    {
        $this->tab = $tab;
        $this->category = '';
    }

    public function submit(): void
    {
        $this->reset(['description', 'amount', 'category']);
        $this->date = now()->format('Y-m-d');
        $this->open = false;
    }
}; ?>

<div x-data="{ open: $wire.entangle('open').live }">
    {{-- FAB --}}
    <button
        type="button"
        x-on:click="open = true"
        class="fixed bottom-6 right-6 z-50 flex items-center justify-center w-14 h-14 rounded-full bg-indigo-600 hover:bg-indigo-700 text-white shadow-lg hover:shadow-xl transition-all duration-200 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-400/50"
    >
        <svg class="w-6 h-6 transition-transform duration-200" :class="{ 'rotate-45': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
    </button>

    {{-- Overlay --}}
    <div
        x-show="open"
        x-transition:enter="transition-opacity duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-on:click="open = false"
        class="fixed inset-0 z-40 bg-gray-900/50"
    ></div>

    {{-- Drawer --}}
    <div
        x-show="open"
        x-transition:enter="transition-transform duration-300"
        x-transition:enter-start="translate-y-full"
        x-transition:enter-end="translate-y-0"
        x-transition:leave="transition-transform duration-200"
        x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full"
        class="fixed bottom-0 inset-x-0 z-50 max-w-lg mx-auto bg-white dark:bg-gray-800 rounded-t-3xl shadow-2xl"
    >
        <div class="px-6 pt-6 pb-8">
            {{-- Handle --}}
            <div class="flex justify-center mb-5">
                <div class="w-10 h-1 rounded-full bg-gray-300 dark:bg-gray-600"></div>
            </div>

            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                    {{ __('Registrar') }}
                </h3>
                <button type="button" x-on:click="open = false" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Tabs --}}
            <div class="flex bg-gray-100 dark:bg-gray-700/50 rounded-xl p-1 mb-6">
                <button
                    type="button"
                    wire:click="toggleTab('expense')"
                    class="flex-1 py-2.5 text-sm font-medium rounded-lg transition-all duration-150"
                    :class="open && $wire.tab === 'expense' ? 'bg-white dark:bg-gray-700 text-red-600 dark:text-red-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
                >
                    <span class="flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                        {{ __('Gasto') }}
                    </span>
                </button>
                <button
                    type="button"
                    wire:click="toggleTab('saving')"
                    class="flex-1 py-2.5 text-sm font-medium rounded-lg transition-all duration-150"
                    :class="open && $wire.tab === 'saving' ? 'bg-white dark:bg-gray-700 text-emerald-600 dark:text-emerald-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
                >
                    <span class="flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ __('Ahorro') }}
                    </span>
                </button>
            </div>

            {{-- Form --}}
            <form wire:submit="submit" class="space-y-4">
                {{-- Description --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('Descripción') }}
                    </label>
                    <input
                        type="text"
                        wire:model="description"
                        placeholder="{{ __('Ej: Supermercado, Renta...') }}"
                        class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    >
                </div>

                {{-- Amount --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('Monto') }}
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                        <input
                            type="number"
                            step="0.01"
                            wire:model="amount"
                            placeholder="0.00"
                            class="w-full pl-8 pr-4 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        >
                    </div>
                </div>

                {{-- Category --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('Categoría') }}
                    </label>
                    <select
                        wire:model="category"
                        class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    >
                        <option value="">{{ __('Seleccionar categoría') }}</option>
                        <option value="Vivienda">{{ __('Vivienda') }}</option>
                        <option value="Alimentación">{{ __('Alimentación') }}</option>
                        <option value="Transporte">{{ __('Transporte') }}</option>
                        <option value="Entretenimiento">{{ __('Entretenimiento') }}</option>
                        <option value="Salud">{{ __('Salud') }}</option>
                        <option value="Educación">{{ __('Educación') }}</option>
                        <option value="Servicios">{{ __('Servicios') }}</option>
                        <option value="Ahorro">{{ __('Ahorro') }}</option>
                        <option value="Otros">{{ __('Otros') }}</option>
                    </select>
                </div>

                {{-- Date --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('Fecha') }}
                    </label>
                    <input
                        type="date"
                        wire:model="date"
                        class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    >
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full py-2.5 text-sm font-semibold rounded-lg transition-all duration-150"
                    :class="$wire.tab === 'expense' ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-emerald-600 hover:bg-emerald-700 text-white'"
                >
                    <span x-text="$wire.tab === 'expense' ? '{{ __("Registrar Gasto") }}' : '{{ __("Registrar Ahorro") }}'"></span>
                </button>
            </form>
        </div>
    </div>
</div>
