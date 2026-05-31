<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-5 py-2.5 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-lg font-semibold text-sm text-white shadow-sm hover:bg-indigo-500 dark:hover:bg-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-400/20 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-150 ease-in-out active:scale-[0.98]']) }}>
    {{ $slot }}
</button>
