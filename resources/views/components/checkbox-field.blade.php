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
            'rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/50 text-indigo-600 shadow-sm focus:ring-2 focus:ring-indigo-400/20 dark:focus:ring-indigo-500/20 dark:focus:ring-offset-gray-800 transition duration-150 ease-in-out cursor-pointer',
        ]) }}
    />
    <span class="text-sm text-gray-600 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300 transition-colors duration-150">
        {{ __($label) }}
    </span>
</label>
