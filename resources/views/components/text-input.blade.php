@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 dark:text-gray-200 focus:border-indigo-400 dark:focus:border-indigo-500 focus:ring-2 focus:ring-indigo-400/20 dark:focus:ring-indigo-500/20 rounded-lg shadow-sm transition duration-150 ease-in-out']) }}>
