<div>
    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="rounded-2xl bg-white dark:bg-gray-800 p-5 shadow-sm border" style="border-color: var(--color-border)">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Gastado') }}</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                ${{ number_format($transactions->sum(fn($t) => $t->type === 'expense' ? $t->amount : 0), 2) }}
            </p>
        </div>
        <div class="rounded-2xl bg-white dark:bg-gray-800 p-5 shadow-sm border" style="border-color: var(--color-border)">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Ingresos') }}</p>
            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">
                +${{ number_format($transactions->sum(fn($t) => $t->type === 'income' ? $t->amount : 0), 2) }}
            </p>
        </div>
        <div class="rounded-2xl bg-white dark:bg-gray-800 p-5 shadow-sm border" style="border-color: var(--color-border)">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Transacciones') }}</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $transactions->total() }}</p>
        </div>
        <div class="rounded-2xl bg-white dark:bg-gray-800 p-5 shadow-sm border" style="border-color: var(--color-border)">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Categorías') }}</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $categories->count() }}</p>
        </div>
    </div>

    {{-- Filters --}}
    @php
        $categoriesJson = $categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'color' => $c->color])->toJson();
    @endphp
    <div class="rounded-2xl bg-white dark:bg-gray-800 shadow-sm mb-6" style="border: 2px solid var(--color-border)">
        <div x-data="{
            open: false,
            search: @entangle('search').live,
            categoryFilter: @entangle('categoryFilter').live,
            monthFilter: @entangle('monthFilter').live,
            typeFilter: @entangle('typeFilter').live,
            categorySearch: '',
            categoriesList: {{ $categoriesJson }},
            get filteredCategories() {
                if (!this.categorySearch) return this.categoriesList
                return this.categoriesList.filter(c => c.name.toLowerCase().includes(this.categorySearch.toLowerCase()))
            },
            get selectedCategory() {
                return this.categoriesList.find(c => c.id == this.categoryFilter)
            },
            get selectedType() {
                const types = { all: '{{ __("Todos") }}', expense: '{{ __("Gastos") }}', income: '{{ __("Ingresos") }}' }
                return types[this.typeFilter]
            },
            get activeFilters() {
                let count = 0
                if (this.search) count++
                if (this.categoryFilter !== 'all') count++
                if (this.monthFilter) count++
                if (this.typeFilter !== 'all') count++
                return count
            },
            clearAll() {
                this.search = ''
                this.categoryFilter = 'all'
                this.monthFilter = ''
                this.typeFilter = 'all'
            }
        }">
            {{-- Filter Header --}}
            <div
                x-on:click="open = !open"
                class="flex items-center justify-between px-5 py-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150"
            >
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg" style="background-color: var(--color-primary-light)">
                        <svg class="w-5 h-5" style="color: var(--color-primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Filtros de búsqueda') }}</span>
                        <p x-show="activeFilters === 0" class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('Haz clic para filtrar transacciones') }}</p>
                        <p x-show="activeFilters > 0" class="text-xs mt-0.5" style="color: var(--color-primary)">
                            <span x-text="activeFilters"></span> {{ __('filtro activo') }}<span x-show="activeFilters > 1">s</span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $transactions->total() }} {{ __('resultados') }}</span>
                    <button
                        x-show="activeFilters > 0"
                        x-transition
                        type="button"
                        x-on:click.stop="clearAll()"
                        class="text-xs font-medium px-2.5 py-1 rounded-full bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/50 transition-colors duration-150"
                    >
                        {{ __('Limpiar') }}
                    </button>
                    <svg
                        class="w-5 h-5 text-gray-400 transition-transform duration-200"
                        :class="{ 'rotate-180': open }"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </div>
            </div>

            {{-- Active Filter Chips --}}
            <div x-show="activeFilters > 0" x-transition class="px-5 pb-3 flex flex-wrap gap-2">
                <template x-if="search">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        <span x-text="search"></span>
                        <button type="button" x-on:click="search = ''" class="ml-0.5 hover:text-red-500 transition-colors">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </span>
                </template>
                <template x-if="categoryFilter !== 'all'">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                        <span class="w-2 h-2 rounded-full" :style="'background-color:' + selectedCategory?.color"></span>
                        <span x-text="selectedCategory?.name"></span>
                        <button type="button" x-on:click="categoryFilter = 'all'" class="ml-0.5 hover:text-red-500 transition-colors">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </span>
                </template>
                <template x-if="monthFilter">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                        <span x-text="monthFilter"></span>
                        <button type="button" x-on:click="monthFilter = ''" class="ml-0.5 hover:text-red-500 transition-colors">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </span>
                </template>
                <template x-if="typeFilter !== 'all'">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium"
                          :class="typeFilter === 'expense' ? 'bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400' : 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400'">
                        <span x-text="selectedType"></span>
                        <button type="button" x-on:click="typeFilter = 'all'" class="ml-0.5 hover:text-red-500 transition-colors">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </span>
                </template>
            </div>

            {{-- Filter Panel --}}
            <div
                x-show="open"
                x-collapse
                class="border-t"
                style="border-color: var(--color-border)"
            >
                <div class="p-5 space-y-4">
                    {{-- Search --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">{{ __('Buscar') }}</label>
                        <div class="relative">
                            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input
                                type="text"
                                x-model.debounce.300ms="search"
                                placeholder="{{ __('Buscar por descripción...') }}"
                                class="w-full pl-10 pr-4 py-2.5 text-sm rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-gray-200 placeholder-gray-400 focus:ring-2 focus:border-transparent transition-all duration-150"
                                style="--tw-ring-color: var(--color-primary)"
                            >
                            <button
                                x-show="search"
                                x-transition
                                type="button"
                                x-on:click="search = ''"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        {{-- Category Dropdown --}}
                        <div x-data="{ open: false }" @click.away="open = false" class="relative" style="z-index: 30">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">{{ __('Categoría') }}</label>
                            <button
                                type="button"
                                x-on:click="open = !open"
                                class="w-full flex items-center justify-between px-4 py-2.5 text-sm rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50 text-left transition-all duration-150"
                                :class="open ? 'ring-2 border-transparent' : ''"
                                :style="open ? 'ring-color: var(--color-primary)' : ''"
                            >
                                <span class="flex items-center gap-2" x-show="selectedCategory">
                                    <span class="w-3 h-3 rounded-full shrink-0" :style="'background-color:' + selectedCategory?.color"></span>
                                    <span class="text-gray-900 dark:text-gray-200" x-text="selectedCategory?.name"></span>
                                </span>
                                <span x-show="!selectedCategory" class="text-gray-500 dark:text-gray-400">{{ __('Todas') }}</span>
                                <svg class="w-4 h-4 text-gray-400 transition-transform duration-150" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div
                                x-show="open"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute z-20 mt-1 w-full rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg overflow-hidden"
                            >
                                <div class="p-2 border-b border-gray-100 dark:border-gray-700">
                                    <input
                                        type="text"
                                        x-model="categorySearch"
                                        placeholder="{{ __('Buscar...') }}"
                                        class="w-full px-3 py-2 text-sm rounded-md border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 placeholder-gray-400 focus:ring-1 focus:border-transparent"
                                        style="--tw-ring-color: var(--color-primary)"
                                    >
                                </div>
                                <div class="max-h-48 overflow-y-auto">
                                    <button
                                        type="button"
                                        x-on:click="categoryFilter = 'all'; open = false; categorySearch = ''"
                                        class="w-full flex items-center gap-2 px-3 py-2 text-sm text-left hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-100"
                                        :class="categoryFilter === 'all' ? 'font-medium' : 'text-gray-700 dark:text-gray-300'"
                                        :style="categoryFilter === 'all' ? 'color: var(--color-primary); background-color: var(--color-primary-light)' : ''"
                                    >
                                        {{ __('Todas las categorías') }}
                                    </button>
                                    <template x-for="cat in filteredCategories" :key="cat.id">
                                        <button
                                            type="button"
                                            x-on:click="categoryFilter = cat.id; open = false; categorySearch = ''"
                                            class="w-full flex items-center gap-2 px-3 py-2 text-sm text-left hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-100"
                                            :class="categoryFilter == cat.id ? 'font-medium' : 'text-gray-700 dark:text-gray-300'"
                                            :style="categoryFilter == cat.id ? 'color: var(--color-primary); background-color: var(--color-primary-light)' : ''"
                                        >
                                            <span class="w-3 h-3 rounded-full shrink-0" :style="'background-color:' + cat.color"></span>
                                            <span x-text="cat.name" class="flex-1"></span>
                                            <svg x-show="categoryFilter == cat.id" class="w-4 h-4" style="color: var(--color-primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- Month Input --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">{{ __('Mes') }}</label>
                            <div class="relative">
                                <input
                                    type="month"
                                    x-model="monthFilter"
                                    class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-gray-200 focus:ring-2 focus:border-transparent transition-all duration-150"
                                    style="--tw-ring-color: var(--color-primary)"
                                >
                                <button
                                    x-show="monthFilter"
                                    x-transition
                                    type="button"
                                    x-on:click="monthFilter = ''"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Type Dropdown --}}
                        <div x-data="{ open: false }" @click.away="open = false" class="relative" style="z-index: 30">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">{{ __('Tipo') }}</label>
                            <button
                                type="button"
                                x-on:click="open = !open"
                                class="w-full flex items-center justify-between px-4 py-2.5 text-sm rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50 text-left transition-all duration-150"
                                :class="open ? 'ring-2 border-transparent' : ''"
                                :style="open ? 'ring-color: var(--color-primary)' : ''"
                            >
                                <span x-text="selectedType" :class="typeFilter !== 'all' ? 'font-medium' : 'text-gray-500 dark:text-gray-400'"></span>
                                <svg class="w-4 h-4 text-gray-400 transition-transform duration-150" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div
                                x-show="open"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute z-20 mt-1 w-full rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg overflow-hidden"
                            >
                                <template x-for="[value, label] in [['all', '{{ __('Todos') }}'], ['expense', '{{ __('Gastos') }}'], ['income', '{{ __('Ingresos') }}']]" :key="value">
                                    <button
                                        type="button"
                                        x-on:click="typeFilter = value; open = false"
                                        class="w-full flex items-center justify-between px-3 py-2 text-sm text-left hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-100"
                                        :class="typeFilter === value ? 'font-medium' : 'text-gray-700 dark:text-gray-300'"
                                        :style="typeFilter === value ? 'color: var(--color-primary); background-color: var(--color-primary-light)' : ''"
                                    >
                                        <span x-text="label"></span>
                                        <svg x-show="typeFilter === value" class="w-4 h-4" style="color: var(--color-primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Transactions Table --}}
    <div class="rounded-2xl bg-white dark:bg-gray-800 shadow-sm border overflow-hidden" style="border-color: var(--color-border)">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b bg-gray-50 dark:bg-gray-800/50" style="border-color: var(--color-border)">
                        <th wire:click="sortBy('transaction_date')" class="text-left px-5 py-3.5 font-medium text-gray-500 dark:text-gray-400 cursor-pointer hover:text-gray-700 dark:hover:text-gray-200 transition-colors duration-150">
                            <div class="flex items-center gap-1">
                                {{ __('Fecha') }}
                                @if($sortField === 'transaction_date')
                                    <svg class="w-3.5 h-3.5 {{ $sortDirection === 'asc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th wire:click="sortBy('description')" class="text-left px-5 py-3.5 font-medium text-gray-500 dark:text-gray-400 cursor-pointer hover:text-gray-700 dark:hover:text-gray-200 transition-colors duration-150">
                            <div class="flex items-center gap-1">
                                {{ __('Descripción') }}
                                @if($sortField === 'description')
                                    <svg class="w-3.5 h-3.5 {{ $sortDirection === 'asc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="text-left px-5 py-3.5 font-medium text-gray-500 dark:text-gray-400">{{ __('Categoría') }}</th>
                        <th wire:click="sortBy('type')" class="text-left px-5 py-3.5 font-medium text-gray-500 dark:text-gray-400 cursor-pointer hover:text-gray-700 dark:hover:text-gray-200 transition-colors duration-150">
                            <div class="flex items-center gap-1">
                                {{ __('Tipo') }}
                                @if($sortField === 'type')
                                    <svg class="w-3.5 h-3.5 {{ $sortDirection === 'asc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th wire:click="sortBy('amount')" class="text-right px-5 py-3.5 font-medium text-gray-500 dark:text-gray-400 cursor-pointer hover:text-gray-700 dark:hover:text-gray-200 transition-colors duration-150">
                            <div class="flex items-center justify-end gap-1">
                                {{ __('Monto') }}
                                @if($sortField === 'amount')
                                    <svg class="w-3.5 h-3.5 {{ $sortDirection === 'asc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="text-right px-5 py-3.5 font-medium text-gray-500 dark:text-gray-400">{{ __('Acción') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr class="border-b last:border-0 transition-colors duration-150 hover:bg-gray-50 dark:hover:bg-gray-700/30" style="border-color: var(--color-border)">
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-400 whitespace-nowrap">
                                {{ $transaction->transaction_date->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4 font-medium text-gray-900 dark:text-gray-200">
                                {{ $transaction->description ?: __('Sin descripción') }}
                            </td>
                            <td class="px-5 py-4">
                                @if($transaction->category)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium"
                                          style="background-color: {{ $transaction->category->color }}20; color: {{ $transaction->category->color }}">
                                        {{ $transaction->category->name }}
                                    </span>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium {{ $transaction->type === 'income' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-rose-50 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400' }}">
                                    {{ $transaction->type === 'income' ? __('Ingreso') : __('Gasto') }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right font-medium {{ $transaction->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $transaction->type === 'income' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button wire:click="deleteTransaction('{{ $transaction->id }}')"
                                            wire:confirm="¿Eliminar esta transacción?"
                                            class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors duration-150">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('No se encontraron transacciones') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($transactions->hasPages())
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-4 border-t bg-gray-50 dark:bg-gray-800/50" style="border-color: var(--color-border)">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Mostrando') }}
                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $transactions->firstItem() }}</span>
                    {{ __('a') }}
                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $transactions->lastItem() }}</span>
                    {{ __('de') }}
                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $transactions->total() }}</span>
                </p>
                <div>
                    {{ $transactions->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
