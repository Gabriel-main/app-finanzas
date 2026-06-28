{{-- @responsable @especialista-frontend --}}
<div>
    <div class="max-w-2xl mx-auto space-y-6">

        {{-- Header --}}
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Mi Perfil') }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ __('Gestiona tu cuenta, cuentas bancarias y categorías') }}
            </p>
        </div>

        {{-- Tabs --}}
        <div class="flex bg-gray-100 dark:bg-gray-700/50 rounded-2xl p-1 gap-1">
            <button
                type="button"
                wire:click="setActiveTab('profile')"
                class="flex-1 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200"
                :class="$wire.activeTab === 'profile' ? 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
            >
                {{ __('Perfil') }}
            </button>
            <button
                type="button"
                wire:click="setActiveTab('accounts')"
                class="flex-1 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200"
                :class="$wire.activeTab === 'accounts' ? 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
            >
                {{ __('Cuentas') }}
            </button>
            <button
                type="button"
                wire:click="setActiveTab('categories')"
                class="flex-1 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200"
                :class="$wire.activeTab === 'categories' ? 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
            >
                {{ __('Categorías') }}
            </button>
        </div>

        {{-- ═══════════ TAB: Profile ═══════════ --}}
        <div x-show="$wire.activeTab === 'profile'" class="space-y-6">

            {{-- Name & Email --}}
            <div class="rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm border" style="border-color: var(--color-border)">
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">{{ __('Información Personal') }}</h4>
                <form wire:submit="saveProfile" class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">{{ __('Nombre') }}</label>
                        <input
                            type="text"
                            wire:model="name"
                            class="w-full px-4 py-2.5 text-sm rounded-lg border bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-2 focus:border-transparent"
                            style="border-color: var(--color-border); --tw-ring-color: var(--color-primary)"
                        >
                        @error('name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">{{ __('Correo Electrónico') }}</label>
                        <input
                            type="email"
                            wire:model="email"
                            class="w-full px-4 py-2.5 text-sm rounded-lg border bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-2 focus:border-transparent"
                            style="border-color: var(--color-border); --tw-ring-color: var(--color-primary)"
                        >
                        @error('email')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="px-5 py-2 rounded-lg text-white text-sm font-semibold transition-all duration-150 hover:shadow-md"
                            style="background-color: var(--color-primary)"
                        >
                            {{ __('Guardar') }}
                        </button>
                    </div>
                </form>
            </div>

            {{-- Change Password --}}
            <div class="rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm border" style="border-color: var(--color-border)">
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">{{ __('Cambiar Contraseña') }}</h4>
                <form wire:submit="updatePassword" class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">{{ __('Contraseña Actual') }}</label>
                        <input
                            type="password"
                            wire:model="currentPassword"
                            class="w-full px-4 py-2.5 text-sm rounded-lg border bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-2 focus:border-transparent"
                            style="border-color: var(--color-border); --tw-ring-color: var(--color-primary)"
                        >
                        @error('currentPassword')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">{{ __('Nueva Contraseña') }}</label>
                        <input
                            type="password"
                            wire:model="newPassword"
                            class="w-full px-4 py-2.5 text-sm rounded-lg border bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-2 focus:border-transparent"
                            style="border-color: var(--color-border); --tw-ring-color: var(--color-primary)"
                        >
                        @error('newPassword')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">{{ __('Confirmar Nueva Contraseña') }}</label>
                        <input
                            type="password"
                            wire:model="newPasswordConfirmation"
                            class="w-full px-4 py-2.5 text-sm rounded-lg border bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-2 focus:border-transparent"
                            style="border-color: var(--color-border); --tw-ring-color: var(--color-primary)"
                        >
                    </div>
                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="px-5 py-2 rounded-lg text-white text-sm font-semibold transition-all duration-150 hover:shadow-md"
                            style="background-color: var(--color-primary)"
                        >
                            {{ __('Actualizar Contraseña') }}
                        </button>
                    </div>
                </form>
            </div>

            {{-- Theme Toggle (Mobile Only) --}}
            <div class="lg:hidden rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm border" style="border-color: var(--color-border)">
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">{{ __('Apariencia') }}</h4>
                <div class="flex items-center justify-between" x-data="{ isDark: document.documentElement.classList.contains('dark') }">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700">
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.752 15.002A9.72 9.72 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ __('Modo Oscuro') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Cambia entre tema claro y oscuro') }}</p>
                        </div>
                    </div>
                    <button
                        type="button"
                        x-on:click="window.toggleTheme(); isDark = document.documentElement.classList.contains('dark')"
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2"
                        :class="isDark ? 'bg-indigo-600 focus:ring-indigo-500' : 'bg-gray-200 focus:ring-gray-400'"
                    >
                        <span
                            class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200 shadow-sm"
                            :class="isDark ? 'translate-x-6' : 'translate-x-1'"
                        ></span>
                    </button>
                </div>
            </div>
        </div>

        {{-- ═══════════ TAB: Accounts ═══════════ --}}
        <div x-show="$wire.activeTab === 'accounts'" class="space-y-6">
            <div class="rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm border" style="border-color: var(--color-border)">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Mis Cuentas') }}</h4>
                    <button
                        type="button"
                        wire:click="toggleAccountForm"
                        class="text-xs font-medium transition-colors duration-150"
                        style="color: var(--color-primary)"
                    >
                        <span x-text="$wire.showAccountForm ? '{{ __("Cancelar") }}' : '+ {{ __("Nueva") }}'"></span>
                    </button>
                </div>

                {{-- New Account Form --}}
                <div
                    x-show="$wire.showAccountForm"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-3 p-4 rounded-xl bg-gray-50 dark:bg-gray-700/30 ring-1 ring-black/5 mb-4"
                >
                    <div>
                        <input
                            type="text"
                            wire:model="newAccountName"
                            placeholder="{{ __('Nombre de la cuenta') }}"
                            x-on:keypress="$event.key.match(/[0-9]/) && $event.preventDefault()"
                            x-on:paste="$event.clipboardData.getData('text').match(/[0-9]/) && $event.preventDefault()"
                            class="w-full px-4 py-2.5 text-sm rounded-lg border-0 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 placeholder-gray-400 focus:ring-2 transition-all duration-150"
                            style="--tw-ring-color: var(--color-primary)"
                        >
                        @error('newAccountName')
                            <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <select
                            wire:model="currencyId"
                            class="w-full px-4 py-2.5 text-sm rounded-lg border-0 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-2 transition-all duration-150"
                            style="--tw-ring-color: var(--color-primary)"
                        >
                            <option value="">{{ __('Seleccionar moneda') }}</option>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}">{{ $currency->symbol }} {{ $currency->name }}</option>
                            @endforeach
                        </select>
                        @error('currencyId')
                            <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                    <button
                        type="button"
                        wire:click="createAccount"
                        class="w-full px-4 py-2.5 text-xs font-semibold rounded-lg text-white transition-all duration-150 hover:shadow-md"
                        style="background-color: var(--color-primary)"
                    >
                        {{ __('Crear cuenta') }}
                    </button>
                </div>

                {{-- Accounts List --}}
                <div class="space-y-2">
                    @forelse($accounts as $account)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 dark:bg-gray-700/30 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors duration-150">
                            <div class="flex items-center gap-3">
                                <span class="flex items-center justify-center w-9 h-9 rounded-lg text-xs font-bold text-white" style="background-color: var(--color-primary)">
                                    {{ $account->currency->symbol ?? '$' }}
                                </span>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $account->name }}</p>
                                    <p class="text-[11px] text-gray-400 dark:text-gray-500">{{ $account->currency->name ?? 'Moneda' }} · {{ number_format($account->balance, 2) }}</p>
                                </div>
                            </div>
                            <button
                                type="button"
                                wire:click="deleteAccount({{ $account->id }})"
                                wire:confirm="¿Eliminar esta cuenta?"
                                class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-150"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-10 h-10 mx-auto text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                            </svg>
                            <p class="text-sm text-gray-400 dark:text-gray-500">{{ __('No tienes cuentas creadas') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ═══════════ TAB: Categories ═══════════ --}}
        <div x-show="$wire.activeTab === 'categories'" class="space-y-6">
            <div class="rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm border" style="border-color: var(--color-border)">

                {{-- Category Sub-Tabs --}}
                <div class="flex items-center justify-between mb-4">
                    <div class="flex bg-gray-100 dark:bg-gray-700/50 rounded-xl p-0.5 gap-0.5">
                        <button
                            type="button"
                            wire:click="toggleCategoryTab('expense')"
                            class="px-4 py-1.5 text-xs font-semibold rounded-lg transition-all duration-200"
                            :class="$wire.categoryTab === 'expense' ? 'bg-white dark:bg-gray-700 text-red-500 dark:text-red-400 shadow-sm' : 'text-gray-500 dark:text-gray-400'"
                        >
                            {{ __('Gastos') }}
                        </button>
                        <button
                            type="button"
                            wire:click="toggleCategoryTab('income')"
                            class="px-4 py-1.5 text-xs font-semibold rounded-lg transition-all duration-200"
                            :class="$wire.categoryTab === 'income' ? 'bg-white dark:bg-gray-700 text-emerald-500 dark:text-emerald-400 shadow-sm' : 'text-gray-500 dark:text-gray-400'"
                        >
                            {{ __('Ingresos') }}
                        </button>
                    </div>
                    <button
                        type="button"
                        wire:click="toggleCategoryForm"
                        class="text-xs font-medium transition-colors duration-150"
                        style="color: var(--color-primary)"
                    >
                        <span x-text="$wire.showCategoryForm ? '{{ __("Cancelar") }}' : '+ {{ __("Nueva") }}'"></span>
                    </button>
                </div>

                {{-- New Category Form --}}
                <div
                    x-show="$wire.showCategoryForm"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="space-y-3 p-4 rounded-xl bg-gray-50 dark:bg-gray-700/30 ring-1 ring-black/5 mb-4"
                >
                    <div>
                        <input
                            type="text"
                            wire:model="newCategoryName"
                            placeholder="{{ __('Nombre de la categoría') }}"
                            x-on:keypress="$event.key.match(/[0-9]/) && $event.preventDefault()"
                            x-on:paste="$event.clipboardData.getData('text').match(/[0-9]/) && $event.preventDefault()"
                            class="w-full px-4 py-2.5 text-sm rounded-lg border-0 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 placeholder-gray-400 focus:ring-2 transition-all duration-150"
                            style="--tw-ring-color: var(--color-primary)"
                        >
                        @error('newCategoryName')
                            <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2">
                            <input
                                type="color"
                                wire:model="newCategoryColor"
                                class="w-8 h-8 rounded-lg cursor-pointer border-2 border-white dark:border-gray-800 shadow-sm"
                            >
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('Color') }}</span>
                        </div>
                        <div class="flex-1"></div>
                        <button
                            type="button"
                            wire:click="createCategory"
                            class="px-4 py-2 text-xs font-semibold rounded-lg text-white transition-all duration-150 hover:shadow-md"
                            style="background-color: var(--color-primary)"
                        >
                            {{ __('Crear') }}
                        </button>
                    </div>
                </div>

                {{-- Categories List --}}
                <div class="space-y-2">
                    @forelse($categories as $category)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 dark:bg-gray-700/30 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors duration-150">
                            <div class="flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full shrink-0" style="background-color: {{ $category->color ?? '#6b7280' }}"></span>
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $category->name }}</span>
                            </div>
                            <button
                                type="button"
                                wire:click="deleteCategory({{ $category->id }})"
                                wire:confirm="¿Eliminar esta categoría?"
                                class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-150"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-10 h-10 mx-auto text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 6h.008v.008H6V6z" />
                            </svg>
                            <p class="text-sm text-gray-400 dark:text-gray-500">{{ __('No hay categorías de este tipo') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
