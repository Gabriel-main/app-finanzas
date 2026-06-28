@props([
    'id' => '',
    'label' => '',
    'model' => '',
])

<label for="{{ $id }}" class="inline-flex items-center gap-2 cursor-pointer group">
    <input
        id="{{ $id }}"
        type="checkbox"
        @if($model) wire:model="{{ $model }}" @endif
        {{ $attributes->except(['wire:model'])->class([
            'rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/50 shadow-sm transition duration-150 ease-in-out cursor-pointer',
        ]) }}
        style="accent-color: var(--color-primary); --tw-ring-color: var(--color-primary); --tw-ring-opacity: 0.2;"
    />
    <span class="text-sm text-gray-600 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300 transition-colors duration-150">
        {{ __($label) }}
    </span>
</label>
