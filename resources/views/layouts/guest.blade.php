@php
    $globalSettings = \App\Models\Setting::whereNull('user_id')->first();
    $appName = $globalSettings?->app_name ?? config('app.name', 'Laravel');
    $logoPath = $globalSettings?->logo_path;
    $primaryColor = $globalSettings?->primary_color ?? '#6366f1';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Inicia sesión en {{ $appName }} para gestionar tus finanzas de forma inteligente.">

        <title>{{ $appName }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --color-primary: {{ $primaryColor }};
                --color-primary-rgb: {{ implode(',', sscanf($primaryColor, '#%02x%02x%02x')) }};
                --color-primary-light: {{ $primaryColor }}15;
            }
            .guest-bg {
                background: linear-gradient(to bottom right, {{ $primaryColor }}08, white, {{ $primaryColor }}05);
            }
            .dark .guest-bg {
                background: #030712;
            }
        </style>

        <script>
            if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="relative min-h-screen flex flex-col justify-center items-center px-4 py-8 overflow-hidden guest-bg">

            {{-- Decorative background shapes --}}
            <div class="absolute top-0 left-0 w-72 h-72 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2 pointer-events-none" style="background-color: {{ $primaryColor }}20"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 rounded-full blur-3xl translate-x-1/3 translate-y-1/3 pointer-events-none" style="background-color: {{ $primaryColor }}15"></div>
            <div class="absolute top-1/2 left-1/2 w-64 h-64 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2 pointer-events-none" style="background-color: {{ $primaryColor }}10"></div>

            {{-- Top navigation --}}
            <div class="absolute top-4 left-4 z-10">
                <a href="/" wire:navigate class="inline-flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    {{ __('Volver') }}
                </a>
            </div>
            <div class="absolute top-4 right-4 z-10">
                <x-theme-toggle />
            </div>

            {{-- Main card --}}
            <div class="w-full sm:max-w-md relative z-10">
                {{-- Logo --}}
                <div class="flex justify-center mb-6">
                    <a href="/" wire:navigate class="group flex flex-col items-center gap-3">
                        <div class="relative">
                            <div class="absolute inset-0 rounded-2xl blur-lg group-hover:blur-xl transition-all duration-300" style="background-color: {{ $primaryColor }}20"></div>
                            @if($logoPath)
                                <img src="{{ asset('storage/' . $logoPath) }}" alt="{{ $appName }}" class="relative h-14 w-14 rounded-2xl object-cover transition-transform duration-300 group-hover:scale-105">
                            @else
                                <x-application-logo class="relative h-14 w-14 transition-transform duration-300 group-hover:scale-105" style="color: {{ $primaryColor }}" />
                            @endif
                        </div>
                        <span class="text-lg font-semibold text-gray-700 dark:text-gray-200 tracking-tight">{{ $appName }}</span>
                    </a>
                </div>

                {{-- Form card --}}
                <div class="bg-white/80 dark:bg-gray-800/60 backdrop-blur-xl rounded-2xl shadow-xl dark:shadow-black/30 border border-white/60 dark:border-gray-700/40 p-8 sm:p-10" style="box-shadow: 0 20px 60px -15px {{ $primaryColor }}15">
                    {{ $slot }}
                </div>

                {{-- Footer --}}
                <p class="mt-6 text-center text-xs text-gray-400 dark:text-gray-500">
                    {{ __('Gestión financiera segura') }}
                </p>
            </div>
        </div>
    </body>
</html>
