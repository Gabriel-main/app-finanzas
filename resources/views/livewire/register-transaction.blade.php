<div
    x-data="{
        dropdownOpen: false,
        search: '',
        categoryId: @entangle('category').live,
        categories: @js($categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'color' => $c->color])),
        get selected() {
            return this.categories.find(c => c.id == this.categoryId)
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
        clearSelection() {
            this.categoryId = ''
            this.search = ''
        }
    }"
    x-effect="$wire.set('category', categoryId)"
    x-on:categories-updated.window="categories = $event.detail.categories"
    x-init="
        $watch('$wire.categories', (val) => {
            categories = val.map(c => ({id: c.id, name: c.name, color: c.color}))
        })
    "
>
    {{-- FAB --}}
    <button
        type="button"
        x-on:click="$wire.set('open', true)"
        class="fixed bottom-6 right-6 z-50 flex items-center justify-center w-14 h-14 rounded-full text-white shadow-lg hover:shadow-xl transition-all duration-200 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2"
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
        x-transition:enter-start="opacity-0 scale-90 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-90 translate-y-4"
        class="fixed z-50 inset-0 m-auto w-full max-w-md h-fit max-h-[90vh] overflow-y-auto bg-white dark:bg-gray-800 rounded-2xl shadow-2xl"
    >
        <div class="px-6 py-6">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                    {{ __('Registrar') }}
                </h3>
                <button type="button" x-on:click="$wire.set('open', false)" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150">
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
                    :class="$wire.tab === 'expense' ? 'bg-white dark:bg-gray-700 text-red-600 dark:text-red-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
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
                    wire:click="toggleTab('income')"
                    class="flex-1 py-2.5 text-sm font-medium rounded-lg transition-all duration-150"
                    :class="$wire.tab === 'income' ? 'bg-white dark:bg-gray-700 text-emerald-600 dark:text-emerald-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
                >
                    <span class="flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ __('Ingreso') }}
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
                        class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 placeholder-gray-400 focus:ring-2 focus:border-transparent"
                        style="--tw-ring-color: var(--color-primary)"
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
                            class="w-full pl-8 pr-4 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 placeholder-gray-400 focus:ring-2 focus:border-transparent"
                            style="--tw-ring-color: var(--color-primary)"
                        >
                    </div>
                </div>

                {{-- Category --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('Categoría') }}
                        </label>
                        <button
                            type="button"
                            wire:click="toggleCategoryForm"
                            class="text-xs font-medium transition-colors duration-150"
                            style="color: var(--color-primary)"
                        >
                            <span x-text="$wire.showCategoryForm ? '{{ __("Cancelar") }}' : '+ {{ __("Crear categoría") }}'"></span>
                        </button>
                    </div>

                    {{-- Custom Dropdown --}}
                    <div x-show="!$wire.showCategoryForm" class="relative">
                        {{-- Trigger --}}
                        <button
                            type="button"
                            x-on:click="dropdownOpen = !dropdownOpen"
                            class="w-full flex items-center justify-between px-4 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-left focus:ring-2 focus:border-transparent transition-colors duration-150"
                            :class="dropdownOpen ? 'border-transparent' : ''"
                            :style="dropdownOpen ? 'ring-color: var(--color-primary)' : ''"
                        >
                            <span class="flex items-center gap-2" x-show="selected">
                                <span class="w-3 h-3 rounded-full shrink-0" :style="'background-color:' + selected?.color"></span>
                                <span class="text-gray-900 dark:text-gray-200" x-text="selected?.name"></span>
                            </span>
                            <span x-show="!selected" class="text-gray-400 dark:text-gray-500">{{ __('Seleccionar categoría') }}</span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-150" :class="{ 'rotate-180': dropdownOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>

                        {{-- Dropdown --}}
                        <div
                            x-show="dropdownOpen"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            x-on:click.away="dropdownOpen = false"
                            class="absolute z-20 mt-1 w-full rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg overflow-hidden"
                        >
                            {{-- Search --}}
                            <div class="p-2 border-b border-gray-100 dark:border-gray-700">
                                <div class="relative">
                                    <svg class="w-4 h-4 absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    <input
                                        type="text"
                                        x-model="search"
                                        x-ref="searchInput"
                                        placeholder="{{ __('Buscar categoría...') }}"
                                        x-on:click.stop
                                        class="w-full pl-8 pr-3 py-2 text-sm rounded-md border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 placeholder-gray-400 focus:ring-1 focus:border-transparent"
                                        style="--tw-ring-color: var(--color-primary)"
                                    >
                                </div>
                            </div>

                            {{-- Category List --}}
                            <div class="max-h-48 overflow-y-auto">
                                <template x-for="cat in filtered" :key="cat.id">
                                    <button
                                        type="button"
                                        x-on:click="select(cat.id)"
                                        class="w-full flex items-center gap-2.5 px-3 py-2 text-sm text-left hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-100"
                                        :class="categoryId == cat.id ? 'text-gray-900 dark:text-gray-200' : 'text-gray-700 dark:text-gray-300'"
                                        :style="categoryId == cat.id ? 'background-color: var(--color-primary-light)' : ''"
                                    >
                                        <span class="w-3 h-3 rounded-full shrink-0" :style="'background-color:' + cat.color"></span>
                                        <span x-text="cat.name" class="flex-1"></span>
                                        <svg x-show="categoryId == cat.id" class="w-4 h-4" style="color: var(--color-primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </template>

                                {{-- Empty state --}}
                                <div x-show="filtered.length === 0" class="px-3 py-4 text-center">
                                    <p class="text-sm text-gray-400 dark:text-gray-500">{{ __('No se encontraron categorías') }}</p>
                                </div>
                            </div>

                            {{-- Create button --}}
                            <div class="p-2 border-t border-gray-100 dark:border-gray-700">
                                <button
                                    type="button"
                                    x-on:click="dropdownOpen = false; $wire.toggleCategoryForm()"
                                    class="w-full flex items-center gap-2 px-3 py-2 text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-md transition-colors duration-150"
                                    style="color: var(--color-primary)"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                    {{ __('Crear nueva categoría') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- New Category Form --}}
                    <div x-show="$wire.showCategoryForm" x-transition class="space-y-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600">
                        <div>
                            <input
                                type="text"
                                wire:model="newCategoryName"
                                placeholder="{{ __('Nombre de la categoría') }}"
                                class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 placeholder-gray-400 focus:ring-2 focus:border-transparent"
                                style="--tw-ring-color: var(--color-primary)"
                            >
                            @error('newCategoryName')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex items-center gap-3">
                            <label class="text-xs text-gray-500 dark:text-gray-400">{{ __('Color') }}</label>
                            <input
                                type="color"
                                wire:model="newCategoryColor"
                                class="w-8 h-8 rounded cursor-pointer border-0 p-0"
                            >
                            <button
                                type="button"
                                wire:click="createCategory"
                                class="px-3 py-1.5 text-xs font-medium rounded-lg text-white transition-colors duration-150"
                                style="background-color: var(--color-primary)"
                            >
                                {{ __('Crear') }}
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Date --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('Fecha') }}
                    </label>
                    <input
                        type="date"
                        wire:model="date"
                        class="w-full px-4 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-2 focus:border-transparent"
                        style="--tw-ring-color: var(--color-primary)"
                    >
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full py-2.5 text-sm font-semibold rounded-lg text-white transition-all duration-150"
                    :class="$wire.tab === 'expense' ? 'hover:opacity-90' : 'hover:opacity-90'"
                    :style="$wire.tab === 'expense' ? 'background-color: #ef4444' : 'background-color: #10b981'"
                >
                    <span x-text="$wire.tab === 'expense' ? '{{ __("Registrar Gasto") }}' : '{{ __("Registrar Ingreso") }}'"></span>
                </button>
            </form>
        </div>
    </div>
</div>
