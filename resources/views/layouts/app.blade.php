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
    <body class="font-sans antialiased">
        <div x-data="{ sidebarOpen: false }" class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <x-sidebar />

            <!-- Mobile overlay -->
            <div
                x-show="sidebarOpen"
                x-on:click="sidebarOpen = false"
                class="fixed inset-0 z-20 bg-gray-900/50 lg:hidden"
                x-transition:enter="transition-opacity duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                style="display: none;"
            ></div>

            <!-- Mobile header -->
            <div class="sticky top-0 z-10 flex items-center gap-3 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 h-16 lg:hidden">
                <button
                    type="button"
                    x-on:click="sidebarOpen = !sidebarOpen"
                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors duration-150"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <span class="font-semibold text-gray-800 dark:text-white">{{ config('app.name') }}</span>
            </div>

            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main class="lg:ml-64">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
