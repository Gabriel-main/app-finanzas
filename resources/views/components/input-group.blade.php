@props([
    'type' => 'text',
    'id' => '',
    'label' => '',
    'error' => null,
    'autocomplete' => '',
    'required' => false,
    'autofocus' => false,
    'placeholder' => '',
    'disabled' => false,
    'model' => '',
])

<div>
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
            {{ __($label) }}
        </label>
    @endif

    <div class="relative">
        @if(isset($icon))
            <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400 dark:text-gray-500">
                {{ $icon }}
            </div>
        @endif

        <input
            id="{{ $id }}"
            type="{{ $type }}"
            name="{{ $id }}"
            placeholder="{{ $placeholder }}"
            autocomplete="{{ $autocomplete }}"
            @if($required) required @endif
            @if($autofocus) autofocus @endif
            @if($disabled) disabled @endif
            @if($model) wire:model="{{ $model }}" @endif
            {{ $attributes->except(['wire:model'])->class([
                'block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 dark:text-gray-200 rounded-lg shadow-sm transition duration-150 ease-in-out focus:border-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary)]/20',
                'pl-10' => isset($icon),
                'pr-10' => $type === 'password',
            ]) }}
        />

        @if($type === 'password')
            <button type="button"
                onclick="const pwd=document.getElementById('{{ $id }}'),btn=this;pwd.type=pwd.type==='password'?'text':'password';btn.querySelectorAll('svg').forEach(s=>s.classList.toggle('hidden'))"
                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none"
                tabindex="-1"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <svg class="hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                </svg>
            </button>
        @endif
    </div>

    @if($error)
        <x-input-error :messages="$error" class="mt-1.5" />
    @endif
</div>
