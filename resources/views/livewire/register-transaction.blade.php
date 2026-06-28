{{-- @responsable @especialista-frontend --}}
<div
    x-data="{
        dropdownOpen: false,
        accountDropdownOpen: false,
        search: '',
        categoryId: @entangle('category').live,
        accountId: @entangle('accountId').live,
        categories: @js($categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'color' => $c->color])),
        accounts: @js($accounts->map(fn($a) => ['id' => $a->id, 'name' => $a->name, 'symbol' => $a->currency->symbol ?? '$', 'currency' => $a->currency->name ?? 'Moneda'])),
        get selected() {
            return this.categories.find(c => c.id == this.categoryId)
        },
        get selectedAccount() {
            return this.accounts.find(a => a.id == this.accountId)
        },
        get filtered() {
            if (!this.search) return this.categories
            return this.categories.filter(c => c.name.toLowerCase().includes(this.search.toLowerCase()))
        },
        select(id) {
            this.categoryId = id
            this.dropdownOpen = false
            this.search = ''
        },
        selectAccount(id) {
            this.accountId = id
            this.accountDropdownOpen = false
        },
        clearSelection() {
            this.categoryId = ''
            this.search = ''
        }
    }"
    x-on:categories-updated.window="categories = $event.detail.categories"
    x-on:accounts-updated.window="
        $wire.get('accounts').then(res => {
            accounts = res.map(a => ({id: a.id, name: a.name, symbol: a.currency?.symbol ?? '$', currency: a.currency?.name ?? 'Moneda'}))
        })
    "
>
    {{-- FAB --}}
    <button
        type="button"
        x-on:click="$wire.set('open', true)"
        class="fixed bottom-24 lg:bottom-6 right-4 sm:right-6 z-50 flex items-center justify-center w-14 h-14 rounded-full text-white shadow-lg hover:shadow-xl transition-all duration-200 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2"
        style="background-color: var(--color-primary); --tw-ring-color: var(--color-primary)"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
    </button>

    {{-- Overlay --}}
    <div
        x-show="$wire.open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-on:click="$wire.set('open', false)"
        class="fixed inset-0 z-40 bg-gray-900/60 backdrop-blur-sm"
    ></div>

    {{-- Modal --}}
    <div
        x-show="$wire.open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="fixed z-50 inset-0 m-auto w-[calc(100%-2rem)] sm:w-full max-w-md h-fit max-h-[90vh] overflow-y-auto modal-scroll bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700/50"
    >
        {{-- Header --}}
        <div class="relative px-6 pt-6 pb-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                        {{ __('Nueva Transacción') }}
                    </h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                        {{ __('Registra un ingreso o gasto') }}
                    </p>
                </div>
                <button
                    type="button"
                    x-on:click="$wire.set('open', false)"
                    class="p-2 rounded-xl text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-150"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="px-6 mb-5">
            <div class="flex bg-gray-100 dark:bg-gray-700/50 rounded-2xl p-1 gap-1">
                <button
                    type="button"
                    wire:click="toggleTab('expense')"
                    class="flex-1 py-3 text-sm font-semibold rounded-xl transition-all duration-200 relative"
                    :class="$wire.tab === 'expense' ? 'bg-white dark:bg-gray-700 text-red-500 dark:text-red-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
                >
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                        {{ __('Gasto') }}
                    </span>
                </button>
                <button
                    type="button"
                    wire:click="toggleTab('income')"
                    class="flex-1 py-3 text-sm font-semibold rounded-xl transition-all duration-200 relative"
                    :class="$wire.tab === 'income' ? 'bg-white dark:bg-gray-700 text-emerald-500 dark:text-emerald-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
                >
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ __('Ingreso') }}
                    </span>
                </button>
            </div>
        </div>

        {{-- Form --}}
        <form wire:submit="submit" class="px-6 pb-6 space-y-5">
            {{-- Amount (Prominent) --}}
            <div class="text-center py-4">
                <label class="block text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">
                    {{ __('Monto') }}
                </label>
                <div class="relative inline-flex items-center">
                    <span class="text-3xl font-light text-gray-300 dark:text-gray-600 mr-1" x-text="selectedAccount?.symbol || '$'"></span>
                    <input
                        type="number"
                        step="0.01"
                        wire:model="amount"
                        placeholder="0.00"
                        class="text-4xl font-bold text-center bg-transparent border-0 focus:outline-none focus:ring-0 w-full max-w-[12rem] text-gray-900 dark:text-white placeholder-gray-200 dark:placeholder-gray-700"
                    >
                </div>
                <div class="w-32 h-0.5 mx-auto mt-2 rounded-full" style="background-color: var(--color-primary-medium)"></div>
                @error('amount')
                    <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Account --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">
                    {{ __('Cuenta') }}
                </label>
                <div class="relative" x-data>
                    <button
                        type="button"
                        x-on:click="accountDropdownOpen = !accountDropdownOpen"
                        class="w-full flex items-center justify-between px-4 py-3 text-sm rounded-xl bg-gray-50 dark:bg-gray-700/50 text-left focus:ring-2 transition-all duration-150"
                        :class="accountDropdownOpen ? 'ring-2 bg-white dark:bg-gray-700' : 'hover:bg-gray-100 dark:hover:bg-gray-700'"
                        :style="accountDropdownOpen ? '--tw-ring-color: var(--color-primary)' : ''"
                    >
                        <span class="flex items-center gap-2.5" x-show="selectedAccount">
                            <span class="flex items-center justify-center w-7 h-7 rounded-lg text-xs font-bold text-white" style="background-color: var(--color-primary)">
                                <span x-text="selectedAccount?.symbol"></span>
                            </span>
                            <div class="flex flex-col">
                                <span class="text-gray-900 dark:text-gray-200 font-medium" x-text="selectedAccount?.name"></span>
                                <span class="text-[10px] text-gray-400 dark:text-gray-500" x-text="selectedAccount?.currency"></span>
                            </div>
                        </span>
                        <span x-show="!selectedAccount" class="text-gray-400 dark:text-gray-500">{{ __('Seleccionar cuenta') }}</span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': accountDropdownOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <div
                        x-show="accountDropdownOpen"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                        x-on:click.away="accountDropdownOpen = false"
                        class="absolute z-20 mt-2 w-full rounded-xl bg-white dark:bg-gray-800 shadow-xl overflow-hidden ring-1 ring-black/5"
                    >
                        <div class="py-1">
                            <template x-for="acc in accounts" :key="acc.id">
                                <button
                                    type="button"
                                    x-on:click="selectAccount(acc.id)"
                                    class="w-full flex items-center gap-3 px-3 py-2.5 text-sm text-left transition-all duration-100"
                                    :class="accountId == acc.id ? 'bg-gray-50 dark:bg-gray-700/50' : 'hover:bg-gray-50 dark:hover:bg-gray-700/30'"
                                >
                                    <span class="flex items-center justify-center w-7 h-7 rounded-lg text-xs font-bold text-white shrink-0" style="background-color: var(--color-primary)">
                                        <span x-text="acc.symbol"></span>
                                    </span>
                                    <div class="flex flex-col flex-1 min-w-0">
                                        <span x-text="acc.name" class="truncate" :class="accountId == acc.id ? 'font-medium text-gray-900 dark:text-gray-100' : 'text-gray-700 dark:text-gray-300'"></span>
                                        <span x-text="acc.currency" class="text-[10px] text-gray-400 dark:text-gray-500"></span>
                                    </div>
                                    <svg x-show="accountId == acc.id" class="w-4 h-4 shrink-0" style="color: var(--color-primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
                @error('accountId')
                    <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">
                    {{ __('Descripción') }}
                </label>
                <input
                    type="text"
                    wire:model="description"
                    placeholder="{{ __('Ej: Supermercado, Renta...') }}"
                    class="w-full px-4 py-3 text-sm rounded-xl border-0 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-gray-200 placeholder-gray-400 focus:ring-2 focus:bg-white dark:focus:bg-gray-700 transition-all duration-150"
                    style="--tw-ring-color: var(--color-primary)"
                >
                @error('description')
                    <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Category --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">
                    {{ __('Categoría') }}
                </label>

                {{-- Custom Dropdown --}}
                <div class="relative">
                    {{-- Trigger --}}
                    <button
                        type="button"
                        x-on:click="dropdownOpen = !dropdownOpen"
                        class="w-full flex items-center justify-between px-4 py-3 text-sm rounded-xl bg-gray-50 dark:bg-gray-700/50 text-left focus:ring-2 transition-all duration-150"
                        :class="dropdownOpen ? 'ring-2 bg-white dark:bg-gray-700' : 'hover:bg-gray-100 dark:hover:bg-gray-700'"
                        :style="dropdownOpen ? '--tw-ring-color: var(--color-primary)' : ''"
                    >
                        <span class="flex items-center gap-2.5" x-show="selected">
                            <span class="w-3 h-3 rounded-full shrink-0 ring-2 ring-white dark:ring-gray-800 shadow-sm" :style="'background-color:' + selected?.color"></span>
                            <span class="text-gray-900 dark:text-gray-200 font-medium" x-text="selected?.name"></span>
                        </span>
                        <span x-show="!selected" class="text-gray-400 dark:text-gray-500">{{ __('Seleccionar categoría') }}</span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': dropdownOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>

                    {{-- Dropdown --}}
                    <div
                        x-show="dropdownOpen"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                        x-on:click.away="dropdownOpen = false"
                        class="absolute z-20 mt-2 w-full rounded-xl bg-white dark:bg-gray-800 shadow-xl overflow-hidden ring-1 ring-black/5"
                    >
                        {{-- Search --}}
                        <div class="p-2.5 border-b border-gray-100 dark:border-gray-700/50">
                            <div class="relative">
                                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input
                                    type="text"
                                    x-model="search"
                                    x-ref="searchInput"
                                    placeholder="{{ __('Buscar...') }}"
                                    x-on:click.stop
                                    class="w-full pl-9 pr-3 py-2 text-sm rounded-lg bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-gray-200 placeholder-gray-400 focus:ring-1 border-0"
                                    style="--tw-ring-color: var(--color-primary)"
                                >
                            </div>
                        </div>

                        {{-- Category List --}}
                        <div class="max-h-52 overflow-y-auto py-1">
                            <template x-for="cat in filtered" :key="cat.id">
                                <button
                                    type="button"
                                    x-on:click="select(cat.id)"
                                    class="w-full flex items-center gap-3 px-3 py-2.5 text-sm text-left transition-all duration-100"
                                    :class="categoryId == cat.id ? 'bg-gray-50 dark:bg-gray-700/50' : 'hover:bg-gray-50 dark:hover:bg-gray-700/30'"
                                >
                                    <span class="w-2.5 h-2.5 rounded-full shrink-0" :style="'background-color:' + cat.color"></span>
                                    <span x-text="cat.name" class="flex-1" :class="categoryId == cat.id ? 'font-medium text-gray-900 dark:text-gray-100' : 'text-gray-700 dark:text-gray-300'"></span>
                                    <svg x-show="categoryId == cat.id" class="w-4 h-4 shrink-0" style="color: var(--color-primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            </template>

                            {{-- Empty state --}}
                            <div x-show="filtered.length === 0" class="px-3 py-6 text-center">
                                <svg class="w-8 h-8 mx-auto text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <p class="text-sm text-gray-400 dark:text-gray-500">{{ __('Sin resultados') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @error('category')
                    <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Date --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">
                    {{ __('Fecha') }}
                </label>
                <input
                    type="date"
                    wire:model="date"
                    class="w-full px-4 py-3 text-sm rounded-xl border-0 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-gray-200 focus:ring-2 focus:bg-white dark:focus:bg-gray-700 transition-all duration-150"
                    style="--tw-ring-color: var(--color-primary)"
                >
                @error('date')
                    <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <button
                type="submit"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-60 cursor-not-allowed"
                class="w-full py-3.5 text-sm font-bold rounded-xl text-white transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0"
                :class="$wire.tab === 'expense' ? 'bg-gradient-to-r from-red-500 to-rose-500 hover:from-red-600 hover:to-rose-600' : 'bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600'"
            >
                <span wire:loading.remove wire:target="submit" class="flex items-center justify-center gap-2">
                    <svg x-show="$wire.tab === 'expense'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                    <svg x-show="$wire.tab === 'income'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span x-text="$wire.tab === 'expense' ? '{{ __("Registrar Gasto") }}' : '{{ __("Registrar Ingreso") }}'"></span>
                </span>
                <span wire:loading wire:target="submit" class="flex items-center justify-center gap-2">
                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"></circle><path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" class="opacity-75"></path></svg>
                    {{ __('Guardando...') }}
                </span>
            </button>
        </form>
    </div>
</div>
