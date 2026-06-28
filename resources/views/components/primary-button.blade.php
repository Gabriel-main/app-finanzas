<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-5 py-2.5 border border-transparent rounded-lg font-semibold text-sm text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-150 ease-in-out active:scale-[0.98]']) }}
    style="background-color: var(--color-primary); --tw-ring-color: var(--color-primary); --tw-ring-opacity: 0.2;"
    onmouseover="this.style.backgroundColor='color-mix(in srgb, var(--color-primary) 85%, black)'"
    onmouseout="this.style.backgroundColor='var(--color-primary)'"
>
    {{ $slot }}
</button>
