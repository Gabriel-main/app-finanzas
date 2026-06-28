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

        <title>{{ $appName }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --color-primary: {{ $primaryColor }};
                --color-primary-rgb: {{ implode(',', sscanf($primaryColor, '#%02x%02x%02x')) }};
            }
        </style>

        <script>
            if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-white dark:bg-gray-950">

            {{-- Navigation --}}
            <nav class="relative z-10 flex items-center justify-between px-6 lg:px-8 py-5" style="border-bottom: 1px solid {{ $primaryColor }}15">
                <div class="flex items-center gap-3">
                    @if($logoPath)
                        <img src="{{ asset('storage/' . $logoPath) }}" alt="{{ $appName }}" class="h-9 w-9 rounded-lg object-cover">
                    @else
                        <x-application-logo class="h-9 w-9" style="color: {{ $primaryColor }}" />
                    @endif
                    <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $appName }}</span>
                </div>

                <div class="flex items-center gap-3">
                    <x-theme-toggle />
                    @auth
                        <a href="{{ url('/dashboard') }}" wire:navigate class="inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-semibold text-white transition-all duration-150 active:scale-[0.98]" style="background-color: {{ $primaryColor }}">
                            {{ __('Ir al Dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" wire:navigate class="inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-semibold text-white transition-all duration-150 active:scale-[0.98]" style="background-color: {{ $primaryColor }}">
                            {{ __('Iniciar sesión') }}
                        </a>
                    @endauth
                </div>
            </nav>

            {{-- Hero Section --}}
            <section class="relative overflow-hidden">
                <div class="absolute inset-0 pointer-events-none">
                    <div class="absolute top-0 left-1/4 w-96 h-96 rounded-full blur-3xl opacity-20" style="background-color: {{ $primaryColor }}"></div>
                    <div class="absolute bottom-0 right-1/4 w-80 h-80 rounded-full blur-3xl opacity-10" style="background-color: {{ $primaryColor }}"></div>
                </div>

                <div class="relative max-w-5xl mx-auto px-6 lg:px-8 pt-20 pb-24 sm:pt-28 sm:pb-32 text-center">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-medium mb-8" style="background-color: {{ $primaryColor }}12; color: {{ $primaryColor }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        {{ __('Gestión financiera inteligente') }}
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white tracking-tight leading-tight">
                        {{ __('Tus finanzas,') }}
                        <span class="block" style="color: {{ $primaryColor }}">{{ __('bajo control.') }}</span>
                    </h1>

                    <p class="mt-6 text-lg sm:text-xl text-gray-500 dark:text-gray-400 max-w-2xl mx-auto leading-relaxed">
                        {{ __('Administra tus gastos, visualiza tus ingresos y toma mejores decisiones financieras con una plataforma simple y poderosa.') }}
                    </p>

                    <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" wire:navigate class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl text-base font-semibold text-white shadow-lg transition-all duration-150 hover:scale-[1.02] active:scale-[0.98]" style="background-color: {{ $primaryColor }}; box-shadow: 0 10px 40px -10px {{ $primaryColor }}80">
                                {{ __('Ir al Dashboard') }}
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                        @else
                            <a href="{{ route('register') }}" wire:navigate class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl text-base font-semibold text-white shadow-lg transition-all duration-150 hover:scale-[1.02] active:scale-[0.98]" style="background-color: {{ $primaryColor }}; box-shadow: 0 10px 40px -10px {{ $primaryColor }}80">
                                {{ __('Comenzar gratis') }}
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                            <a href="{{ route('login') }}" wire:navigate class="inline-flex items-center px-7 py-3.5 rounded-xl text-base font-semibold text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-150">
                                {{ __('Ya tengo una cuenta') }}
                            </a>
                        @endauth
                    </div>
                </div>
            </section>

            {{-- Features Section --}}
            <section class="py-20 sm:py-28">
                <div class="max-w-5xl mx-auto px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white tracking-tight">
                            {{ __('Todo lo que necesitas') }}
                        </h2>
                        <p class="mt-4 text-lg text-gray-500 dark:text-gray-400">
                            {{ __('Herramientas diseñadas para simplificar tu vida financiera') }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        {{-- Feature 1 --}}
                        <div class="group relative p-6 rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 hover:border-transparent transition-all duration-300 hover:shadow-xl dark:hover:shadow-2xl" style="--tw-shadow-color: {{ $primaryColor }}15">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5" style="background-color: {{ $primaryColor }}12">
                                <svg class="w-6 h-6" style="color: {{ $primaryColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('Dashboard Inteligente') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                                {{ __('Visualiza un resumen completo de tus finanzas con gráficas interactivas y métricas en tiempo real.') }}
                            </p>
                        </div>

                        {{-- Feature 2 --}}
                        <div class="group relative p-6 rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 hover:border-transparent transition-all duration-300 hover:shadow-xl dark:hover:shadow-2xl" style="--tw-shadow-color: {{ $primaryColor }}15">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5" style="background-color: {{ $primaryColor }}12">
                                <svg class="w-6 h-6" style="color: {{ $primaryColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('Control de Gastos') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                                {{ __('Registra y categoriza cada gasto para saber exactamente en qué se va tu dinero cada mes.') }}
                            </p>
                        </div>

                        {{-- Feature 3 --}}
                        <div class="group relative p-6 rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 hover:border-transparent transition-all duration-300 hover:shadow-xl dark:hover:shadow-2xl" style="--tw-shadow-color: {{ $primaryColor }}15">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5" style="background-color: {{ $primaryColor }}12">
                                <svg class="w-6 h-6" style="color: {{ $primaryColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('Reportes Visuales') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                                {{ __('Gráficas de ingresos vs egresos que te ayudan a entender tus patrones de gasto.') }}
                            </p>
                        </div>

                        {{-- Feature 4 --}}
                        <div class="group relative p-6 rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 hover:border-transparent transition-all duration-300 hover:shadow-xl dark:hover:shadow-2xl" style="--tw-shadow-color: {{ $primaryColor }}15">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5" style="background-color: {{ $primaryColor }}12">
                                <svg class="w-6 h-6" style="color: {{ $primaryColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 6h.008v.008H6V6z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('Personalización') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                                {{ __('Adapta los colores y la apariencia de la app a tu gusto con temas personalizables.') }}
                            </p>
                        </div>

                        {{-- Feature 5 --}}
                        <div class="group relative p-6 rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 hover:border-transparent transition-all duration-300 hover:shadow-xl dark:hover:shadow-2xl" style="--tw-shadow-color: {{ $primaryColor }}15">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5" style="background-color: {{ $primaryColor }}12">
                                <svg class="w-6 h-6" style="color: {{ $primaryColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('Seguridad') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                                {{ __('Tus datos financieros están protegidos con autenticación segura y encriptación.') }}
                            </p>
                        </div>

                        {{-- Feature 6 --}}
                        <div class="group relative p-6 rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 hover:border-transparent transition-all duration-300 hover:shadow-xl dark:hover:shadow-2xl" style="--tw-shadow-color: {{ $primaryColor }}15">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5" style="background-color: {{ $primaryColor }}12">
                                <svg class="w-6 h-6" style="color: {{ $primaryColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('100% Móvil') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                                {{ __('Diseñada para funcionar perfectamente en tu teléfono, desde cualquier lugar.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- CTA Section --}}
            <section class="py-20">
                <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center">
                    <div class="relative p-10 sm:p-14 rounded-3xl overflow-hidden" style="background: linear-gradient(135deg, {{ $primaryColor }}10, {{ $primaryColor }}05)">
                        <div class="absolute inset-0 pointer-events-none">
                            <div class="absolute top-0 right-0 w-64 h-64 rounded-full blur-3xl opacity-20" style="background-color: {{ $primaryColor }}"></div>
                            <div class="absolute bottom-0 left-0 w-48 h-48 rounded-full blur-3xl opacity-10" style="background-color: {{ $primaryColor }}"></div>
                        </div>
                        <div class="relative">
                            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-4">
                                {{ __('¿Listo para tomar el control?') }}
                            </h2>
                            <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-lg mx-auto">
                                {{ __('Empieza a organizar tus finanzas hoy mismo. Es gratis y sin compromiso.') }}
                            </p>
                            @auth
                                <a href="{{ url('/dashboard') }}" wire:navigate class="inline-flex items-center gap-2 px-8 py-4 rounded-xl text-base font-semibold text-white shadow-lg transition-all duration-150 hover:scale-[1.02] active:scale-[0.98]" style="background-color: {{ $primaryColor }}; box-shadow: 0 10px 40px -10px {{ $primaryColor }}80">
                                    {{ __('Ir al Dashboard') }}
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                            @else
                                <a href="{{ route('register') }}" wire:navigate class="inline-flex items-center gap-2 px-8 py-4 rounded-xl text-base font-semibold text-white shadow-lg transition-all duration-150 hover:scale-[1.02] active:scale-[0.98]" style="background-color: {{ $primaryColor }}; box-shadow: 0 10px 40px -10px {{ $primaryColor }}80">
                                    {{ __('Crear mi cuenta gratis') }}
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </section>

            {{-- Footer --}}
            <footer class="py-8 text-center text-sm text-gray-400 dark:text-gray-500" style="border-top: 1px solid {{ $primaryColor }}10">
                <p>{{ $appName }} &copy; {{ date('Y') }}. {{ __('Todos los derechos reservados.') }}</p>
            </footer>
        </div>
    </body>
</html>
