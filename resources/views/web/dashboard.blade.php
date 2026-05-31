<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6" x-data="dashboardData()" x-init="init()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Saludo --}}
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('¡Bienvenido de nuevo,') }} {{ auth()->user()->name }}!
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ __('Aquí tienes el resumen de tus finanzas') }}
                    </p>
                </div>
                <div class="hidden sm:flex items-center gap-3">
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ now()->format('l, d F Y') }}
                    </span>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <template x-for="(card, index) in stats" :key="index">
                    <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-5 shadow-sm border border-gray-200 dark:border-gray-700 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="p-2 rounded-lg" :class="card.bg">
                                <svg class="w-5 h-5" :class="card.iconColor" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="text-xs font-medium px-2.5 py-0.5 rounded-full" :class="card.trend === 'up' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400'" x-text="card.change"></span>
                        </div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400" x-text="card.label"></p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1" x-text="card.formatted"></p>
                    </div>
                </template>
            </div>

            {{-- Charts Section --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Income vs Expense Chart --}}
                <div class="lg:col-span-2 rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ __('Ingresos vs Gastos') }}
                        </h4>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-full bg-indigo-500"></span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('Ingresos') }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('Gastos') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="relative" style="height: 220px;">
                        <svg class="w-full h-full" viewBox="0 0 600 220" preserveAspectRatio="none">
                            <template x-for="(bar, i) in chartData" :key="i">
                                <g>
                                    <rect
                                        x="3"
                                        :y="220 - (bar.income / maxChart) * 200 - 10"
                                        :width="(600 / chartData.length) - 6"
                                        :height="(bar.income / maxChart) * 200"
                                        fill="#6366f1"
                                        :opacity="hoveredBar === i ? '1' : '0.85'"
                                        rx="4"
                                        class="transition-all duration-200 cursor-pointer"
                                        @mouseenter="hoveredBar = i"
                                        @mouseleave="hoveredBar = null"
                                    />
                                    <rect
                                        x="3"
                                        :y="220 - (bar.expense / maxChart) * 200 - 10"
                                        :width="(600 / chartData.length) - 6"
                                        :height="(bar.expense / maxChart) * 200"
                                        fill="#f43f5e"
                                        :opacity="hoveredBar === i ? '1' : '0.85'"
                                        rx="4"
                                        class="transition-all duration-200 cursor-pointer"
                                        @mouseenter="hoveredBar = i"
                                        @mouseleave="hoveredBar = null"
                                    />
                                </g>
                            </template>
                        </svg>
                        <div class="absolute inset-x-0 bottom-0 flex justify-between text-xs text-gray-400 dark:text-gray-500 pt-2">
                            <template x-for="(bar, i) in chartData" :key="i">
                                <span x-text="bar.month"></span>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Expense Distribution --}}
                <div class="rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                        {{ __('Distribución de Gastos') }}
                    </h4>
                    <div class="space-y-4">
                        <template x-for="(cat, i) in categories" :key="i">
                            <div>
                                <div class="flex items-center justify-between text-sm mb-1.5">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2.5 h-2.5 rounded-full" :style="'background-color: ' + cat.color"></span>
                                        <span class="text-gray-700 dark:text-gray-300" x-text="cat.name"></span>
                                    </div>
                                    <span class="text-gray-500 dark:text-gray-400 font-medium" x-text="cat.percentage + '%'"></span>
                                </div>
                                <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2">
                                    <div class="h-2 rounded-full transition-all duration-500" :style="'width: ' + cat.percentage + '%; background-color: ' + cat.color"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Recent Transactions + Budgets --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Recent Transactions --}}
                <div class="lg:col-span-2 rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ __('Transacciones Recientes') }}
                        </h4>
                        <a href="#" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors duration-150">
                            {{ __('Ver todas') }} &rarr;
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <th class="text-left pb-3 font-medium text-gray-500 dark:text-gray-400">{{ __('Fecha') }}</th>
                                    <th class="text-left pb-3 font-medium text-gray-500 dark:text-gray-400">{{ __('Descripción') }}</th>
                                    <th class="text-left pb-3 font-medium text-gray-500 dark:text-gray-400">{{ __('Categoría') }}</th>
                                    <th class="text-right pb-3 font-medium text-gray-500 dark:text-gray-400">{{ __('Monto') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(tx, i) in transactions" :key="i">
                                    <tr class="border-b border-gray-100 dark:border-gray-700/50 last:border-0 transition-colors duration-150 hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                        <td class="py-3 text-gray-600 dark:text-gray-400" x-text="tx.date"></td>
                                        <td class="py-3 font-medium text-gray-900 dark:text-gray-200" x-text="tx.description"></td>
                                        <td class="py-3">
                                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-medium" :class="tx.categoryClass" x-text="tx.category">
                                            </span>
                                        </td>
                                        <td class="py-3 text-right font-medium" :class="tx.type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400'" x-text="tx.formatted"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Budget Progress --}}
                <div class="rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ __('Presupuestos') }}
                        </h4>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('Este mes') }}</span>
                    </div>
                    <div class="space-y-5">
                        <template x-for="(budget, i) in budgets" :key="i">
                            <div>
                                <div class="flex items-center justify-between text-sm mb-1.5">
                                    <span class="text-gray-700 dark:text-gray-300" x-text="budget.name"></span>
                                    <span class="text-gray-500 dark:text-gray-400 text-xs">
                                        <span x-text="budget.spent"></span> / <span x-text="budget.total"></span>
                                    </span>
                                </div>
                                <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2.5">
                                    <div
                                        class="h-2.5 rounded-full transition-all duration-500"
                                        :class="budget.percentage > 90 ? 'bg-red-500' : budget.percentage > 70 ? 'bg-amber-500' : 'bg-indigo-500'"
                                        :style="'width: ' + Math.min(budget.percentage, 100) + '%'"
                                    ></div>
                                </div>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1" x-text="budget.percentage + '% utilizado'"></p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <livewire:register-transaction />

    <script>
        function dashboardData() {
            return {
                hoveredBar: null,
                maxChart: 0,
                stats: [
                    { label: '{{ __("Saldo Total") }}', value: 45280, formatted: '$45,280.00', change: '+12.5%', trend: 'up', bg: 'bg-indigo-50 dark:bg-indigo-900/30', iconColor: 'text-indigo-600 dark:text-indigo-400' },
                    { label: '{{ __("Ingresos del Mes") }}', value: 12450, formatted: '$12,450.00', change: '+8.2%', trend: 'up', bg: 'bg-emerald-50 dark:bg-emerald-900/30', iconColor: 'text-emerald-600 dark:text-emerald-400' },
                    { label: '{{ __("Gastos del Mes") }}', value: 8320, formatted: '$8,320.00', change: '+3.1%', trend: 'up', bg: 'bg-rose-50 dark:bg-rose-900/30', iconColor: 'text-rose-600 dark:text-rose-400' },
                    { label: '{{ __("Tasa de Ahorro") }}', value: 33.2, formatted: '33.2%', change: '+5.3%', trend: 'up', bg: 'bg-amber-50 dark:bg-amber-900/30', iconColor: 'text-amber-600 dark:text-amber-400' },
                ],
                chartData: [
                    { month: 'Ene', income: 4500, expense: 3200 },
                    { month: 'Feb', income: 5200, expense: 3800 },
                    { month: 'Mar', income: 4800, expense: 2900 },
                    { month: 'Abr', income: 6100, expense: 4100 },
                    { month: 'May', income: 5500, expense: 3500 },
                    { month: 'Jun', income: 12450, expense: 8320 },
                ],
                categories: [
                    { name: '{{ __("Vivienda") }}', percentage: 35, color: '#6366f1' },
                    { name: '{{ __("Alimentación") }}', percentage: 25, color: '#f59e0b' },
                    { name: '{{ __("Transporte") }}', percentage: 15, color: '#10b981' },
                    { name: '{{ __("Entretenimiento") }}', percentage: 12, color: '#f43f5e' },
                    { name: '{{ __("Otros") }}', percentage: 13, color: '#8b5cf6' },
                ],
                transactions: [
                    { date: '28 May', description: '{{ __("Supermercado") }}', category: '{{ __("Alimentación") }}', categoryClass: 'bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400', type: 'expense', value: 156.50, formatted: '-$156.50' },
                    { date: '27 May', description: '{{ __("Servicio de agua") }}', category: '{{ __("Vivienda") }}', categoryClass: 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400', type: 'expense', value: 45.00, formatted: '-$45.00' },
                    { date: '26 May', description: '{{ __("Nómina mensual") }}', category: '{{ __("Ingreso") }}', categoryClass: 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400', type: 'income', value: 4500.00, formatted: '+$4,500.00' },
                    { date: '25 May', description: '{{ __("Uber viajes") }}', category: '{{ __("Transporte") }}', categoryClass: 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400', type: 'expense', value: 32.80, formatted: '-$32.80' },
                    { date: '24 May', description: '{{ __("Netflix") }}', category: '{{ __("Entretenimiento") }}', categoryClass: 'bg-rose-50 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400', type: 'expense', value: 15.99, formatted: '-$15.99' },
                    { date: '23 May', description: '{{ __("Freelance project") }}', category: '{{ __("Ingreso") }}', categoryClass: 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400', type: 'income', value: 1200.00, formatted: '+$1,200.00' },
                ],
                budgets: [
                    { name: '{{ __("Alimentación") }}', spent: '$680', total: '$800', percentage: 85 },
                    { name: '{{ __("Transporte") }}', spent: '$210', total: '$300', percentage: 70 },
                    { name: '{{ __("Entretenimiento") }}', spent: '$145', total: '$200', percentage: 72 },
                    { name: '{{ __("Servicios") }}', spent: '$320', total: '$400', percentage: 80 },
                ],
                init() {
                    this.maxChart = Math.max(...this.chartData.flatMap(d => [d.income, d.expense]));
                }
            }
        }
    </script>
</x-app-layout>
