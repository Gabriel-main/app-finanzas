<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script>
            if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="relative min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 dark:from-gray-950 dark:via-gray-900 dark:to-indigo-950 flex flex-col justify-center items-center px-4 py-8">
            <div class="absolute top-4 left-4">
                <a href="/" wire:navigate class="inline-flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    {{ __('Back') }}
                </a>
            </div>
            <div class="absolute top-4 right-4">
                <x-theme-toggle />
            </div>

            <div class="w-full sm:max-w-md">
                <div class="flex justify-center mb-8">
                    <a href="/" wire:navigate class="group">
                        <x-application-logo class="w-14 h-14 text-indigo-600 dark:text-indigo-400 transition-transform duration-300 group-hover:scale-105" />
                    </a>
                </div>

                <div class="bg-white dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl shadow-indigo-500/10 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50 p-8 sm:p-10">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
