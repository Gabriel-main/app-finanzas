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
        <div x-data="{ toasts: [], addToast(msg, type = 'success') { const id = Date.now(); this.toasts.push({ id, msg, type }); setTimeout(() => this.toasts = this.toasts.filter(t => t.id !== id), 4000); } }"
             x-on:show-toast.window="addToast($event.detail.message, $event.detail.type ?? 'success')"
             class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <x-sidebar />

            <div data-sidebar-content class="transition-all duration-200">
                <div class="sticky top-0 z-40 flex items-center justify-between bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 h-14">
                    <button
                        type="button"
                        onclick="window.toggleSidebar()"
                        class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors duration-150"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <x-theme-toggle />
                </div>

                @if (isset($header))
                    <header class="bg-white dark:bg-gray-800 shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <main>
                    {{ $slot }}
                </main>
            </div>

            <div
                data-sidebar-overlay
                onclick="window.closeSidebar()"
                class="fixed inset-0 z-20 bg-gray-900/50 lg:hidden hidden"
            ></div>

            {{-- Toast Notifications --}}
            <div class="fixed top-4 right-4 z-[100] flex flex-col gap-2">
                <template x-for="toast in toasts" :key="toast.id">
                    <div x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-x-8"
                         x-transition:enter-end="opacity-100 translate-x-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-x-0"
                         x-transition:leave-end="opacity-0 translate-x-8"
                         class="flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg text-sm font-medium min-w-[280px]"
                         :class="{
                            'bg-emerald-50 dark:bg-emerald-900/80 text-emerald-700 dark:text-emerald-200 border border-emerald-200 dark:border-emerald-700': toast.type === 'success',
                            'bg-red-50 dark:bg-red-900/80 text-red-700 dark:text-red-200 border border-red-200 dark:border-red-700': toast.type === 'error',
                            'bg-amber-50 dark:bg-amber-900/80 text-amber-700 dark:text-amber-200 border border-amber-200 dark:border-amber-700': toast.type === 'warning'
                         }">
                        <svg x-show="toast.type === 'success'" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <svg x-show="toast.type === 'error'" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        <span x-text="toast.msg"></span>
                    </div>
                </template>
            </div>
        </div>
    </body>
</html>
