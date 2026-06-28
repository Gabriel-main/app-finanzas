<div>
    <div class="max-w-2xl mx-auto space-y-6">

        {{-- Header --}}
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Personalización') }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                @if($isAdmin)
                    {{ __('Administra la apariencia de toda la aplicación') }}
                @else
                    {{ __('Personaliza el color de tu tema y gráficas') }}
                @endif
            </p>
        </div>

        {{-- Form --}}
        <form wire:submit="save" class="space-y-6">

            {{-- App Name (Admin Only) --}}
            @if($isAdmin)
            <div class="rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm border" style="border-color: var(--color-border)">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Nombre de la Aplicación') }}
                </label>
                <input
                    type="text"
                    wire:model="appName"
                    class="w-full px-4 py-2.5 text-sm rounded-lg border bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-2 focus:border-transparent"
                    style="border-color: var(--color-border); --tw-ring-color: var(--color-primary)"
                >
                @error('appName')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endif

            {{-- Primary Color --}}
            <div class="rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm border" style="border-color: var(--color-border)">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                    {{ __('Color del Tema') }}
                </label>

                @if($isAdmin)
                    {{-- Admin: Color picker libre + predefinidos --}}
                    <div class="flex items-center gap-4">
                        <input
                            type="color"
                            wire:model.live="primaryColor"
                            class="w-12 h-12 rounded-lg cursor-pointer p-0.5"
                            style="border: 2px solid var(--color-border)"
                        >
                        <div class="flex-1">
                            <input
                                type="text"
                                wire:model.live="primaryColor"
                                class="w-full px-4 py-2.5 text-sm rounded-lg border bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 font-mono focus:ring-2 focus:border-transparent"
                                style="border-color: var(--color-border); --tw-ring-color: var(--color-primary)"
                            >
                        </div>
                    </div>
                @endif

                {{-- Preset Colors --}}
                <div class="{{ $isAdmin ? 'mt-4' : '' }}">
                    @if(!$isAdmin)
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">{{ __('Selecciona un color') }}</p>
                    @else
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">{{ __('Colores predefinidos') }}</p>
                    @endif
                    <div class="flex flex-wrap gap-2">
                        @if($isAdmin)
                            @foreach(['#6366f1' => 'Indigo', '#8b5cf6' => 'Violet', '#ec4899' => 'Pink', '#ef4444' => 'Red', '#f97316' => 'Orange', '#eab308' => 'Yellow', '#22c55e' => 'Green', '#14b981' => 'Teal', '#06b6d4' => 'Cyan', '#3b82f6' => 'Blue'] as $color => $name)
                                <button
                                    type="button"
                                    wire:click="setPrimaryColor('{{ $color }}')"
                                    class="w-8 h-8 rounded-full transition-all duration-150 {{ $primaryColor === $color ? 'scale-110' : 'hover:scale-105' }}"
                                    style="background-color: {{ $color }}; border: 2px solid {{ $primaryColor === $color ? 'var(--color-primary)' : 'var(--color-border)' }}"
                                    title="{{ $name }}"
                                ></button>
                            @endforeach
                        @else
                            {{-- User: Solo 5 colores elegantes --}}
                            @foreach(['#6366f1' => 'Índigo', '#3b82f6' => 'Azul', '#10b981' => 'Verde', '#f59e0b' => 'Ámbar', '#ef4444' => 'Rojo'] as $color => $name)
                                <button
                                    type="button"
                                    wire:click="setPrimaryColor('{{ $color }}')"
                                    class="flex items-center gap-2 px-3 py-2 rounded-lg transition-all duration-150 {{ $primaryColor === $color ? 'bg-gray-100 dark:bg-gray-700' : 'hover:bg-gray-50 dark:hover:bg-gray-700/50' }}"
                                    style="border: 2px solid {{ $primaryColor === $color ? 'var(--color-primary)' : 'var(--color-border)' }}"
                                >
                                    <span class="w-4 h-4 rounded-full" style="background-color: {{ $color }}"></span>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $name }}</span>
                                </button>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- Preview --}}
                <div class="mt-4 p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">{{ __('Vista previa') }}</p>
                    <div class="flex items-center gap-2">
                        <button type="button" class="px-4 py-2 rounded-lg text-white text-sm font-medium" style="background-color: {{ $primaryColor }}">
                            Botón principal
                        </button>
                        <button type="button" class="px-4 py-2 rounded-lg text-sm font-medium" style="border: 2px solid {{ $primaryColor }}; color: {{ $primaryColor }}">
                            Secundario
                        </button>
                    </div>
                </div>
            </div>

            {{-- Chart Colors --}}
            <div class="rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm border" style="border-color: var(--color-border)">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                    {{ __('Colores de Gráficas') }}
                </label>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                    {{ __('Personaliza los colores de las barras de ingresos y gastos en el dashboard') }}
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Income Color --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">
                            {{ __('Color de Ingresos') }}
                        </label>
                        <div class="flex items-center gap-3">
                            <input
                                type="color"
                                wire:model.live="chartIncomeColor"
                                class="w-10 h-10 rounded-lg cursor-pointer p-0.5"
                                style="border: 2px solid var(--color-border)"
                            >
                            <input
                                type="text"
                                wire:model.live="chartIncomeColor"
                                class="flex-1 px-3 py-2 text-sm rounded-lg border bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 font-mono focus:ring-2 focus:border-transparent"
                                style="border-color: var(--color-border); --tw-ring-color: var(--color-primary)"
                            >
                        </div>
                    </div>

                    {{-- Expense Color --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">
                            {{ __('Color de Gastos') }}
                        </label>
                        <div class="flex items-center gap-3">
                            <input
                                type="color"
                                wire:model.live="chartExpenseColor"
                                class="w-10 h-10 rounded-lg cursor-pointer p-0.5"
                                style="border: 2px solid var(--color-border)"
                            >
                            <input
                                type="text"
                                wire:model.live="chartExpenseColor"
                                class="flex-1 px-3 py-2 text-sm rounded-lg border bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 font-mono focus:ring-2 focus:border-transparent"
                                style="border-color: var(--color-border); --tw-ring-color: var(--color-primary)"
                            >
                        </div>
                    </div>
                </div>

                {{-- Chart Preview --}}
                <div class="mt-4 p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">{{ __('Vista previa de gráfica') }}</p>
                    <div class="flex items-end gap-2 h-16">
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <div class="w-full rounded-t" style="height: 40px; background-color: {{ $chartIncomeColor }}"></div>
                            <span class="text-[10px] text-gray-500 dark:text-gray-400">{{ __('Ingresos') }}</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <div class="w-full rounded-t" style="height: 28px; background-color: {{ $chartExpenseColor }}"></div>
                            <span class="text-[10px] text-gray-500 dark:text-gray-400">{{ __('Gastos') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Logo (Admin Only) --}}
            @if($isAdmin)
            <div class="rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm border" style="border-color: var(--color-border)">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                    {{ __('Logo') }}
                </label>

                @if($currentLogo && !$logo)
                    <div class="flex items-center gap-4 mb-4">
                        <img src="{{ asset('storage/' . $currentLogo) }}" alt="Logo" class="w-16 h-16 rounded-lg object-cover" style="border: 1px solid var(--color-border)">
                        <button
                            type="button"
                            wire:click="removeLogo"
                            class="text-sm text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition-colors"
                        >
                            {{ __('Eliminar logo') }}
                        </button>
                    </div>
                @endif

                <div class="flex items-center gap-4">
                    <label class="flex flex-col items-center justify-center w-32 h-32 rounded-xl border-2 border-dashed cursor-pointer transition-colors duration-150 bg-gray-50 dark:bg-gray-700/50" style="border-color: var(--color-border)">
                        @if($logo)
                            <img src="{{ $logo->temporaryUrl() }}" class="w-full h-full object-cover rounded-xl">
                        @else
                            <svg class="w-8 h-8 text-gray-400 dark:text-gray-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                            </svg>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('Subir logo') }}</span>
                        @endif
                        <input type="file" wire:model="logo" class="hidden" accept="image/*">
                    </label>
                    <div class="text-xs text-gray-400 dark:text-gray-500">
                        <p>{{ __('PNG, JPG o SVG') }}</p>
                        <p>{{ __('Máx. 2MB') }}</p>
                    </div>
                </div>
                @error('logo')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endif

            {{-- Save --}}
            <div class="flex justify-end">
                <button
                    type="submit"
                    class="px-6 py-2.5 rounded-lg text-white text-sm font-semibold transition-all duration-150 hover:shadow-md"
                    style="background-color: {{ $primaryColor }}"
                >
                    {{ __('Guardar Cambios') }}
                </button>
            </div>
        </form>
    </div>
</div>
