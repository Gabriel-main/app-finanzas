<div>
    {{-- Saludo --}}
    <div class="flex items-center justify-between mb-6">
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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-5 shadow-sm border transition-all duration-200 hover:shadow-md hover:-translate-y-0.5" style="border-color: var(--color-border)">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 rounded-lg" style="background-color: var(--color-primary-light)">
                    <svg class="w-5 h-5" style="color: var(--color-primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Saldo Total') }}</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">${{ number_format($totalBalance, 2) }}</p>
        </div>

        <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-5 shadow-sm border transition-all duration-200 hover:shadow-md hover:-translate-y-0.5" style="border-color: var(--color-border)">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 rounded-lg bg-emerald-50 dark:bg-emerald-900/30">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Ingresos del Mes') }}</p>
            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">${{ number_format($monthlyIncome, 2) }}</p>
        </div>

        <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-5 shadow-sm border transition-all duration-200 hover:shadow-md hover:-translate-y-0.5" style="border-color: var(--color-border)">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 rounded-lg bg-rose-50 dark:bg-rose-900/30">
                    <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Gastos del Mes') }}</p>
            <p class="text-2xl font-bold text-rose-600 dark:text-rose-400 mt-1">${{ number_format($monthlyExpenses, 2) }}</p>
        </div>

        <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-5 shadow-sm border transition-all duration-200 hover:shadow-md hover:-translate-y-0.5" style="border-color: var(--color-border)">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 rounded-lg bg-amber-50 dark:bg-amber-900/30">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                    </svg>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Tasa de Ahorro') }}</p>
            <p class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-1">{{ $savingsRate }}%</p>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {{-- Income vs Expense Chart --}}
        <div class="lg:col-span-2 rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm border" style="border-color: var(--color-border)">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ __('Ingresos vs Gastos') }}
                </h4>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full" style="background-color: var(--color-primary)"></span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('Ingresos') }}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('Gastos') }}</span>
                    </div>
                </div>
            </div>
            <div class="relative" style="height: 220px;">
                @if($maxChart > 0)
                    <svg class="w-full h-full" viewBox="0 0 600 220" preserveAspectRatio="none">
                        @foreach($chartData as $i => $bar)
                            <g>
                                <rect
                                    x="{{ ($i * (600 / count($chartData))) + 3 }}"
                                    y="{{ 220 - ($bar['income'] / $maxChart) * 200 - 10 }}"
                                    width="{{ (600 / count($chartData)) - 6 }}"
                                    height="{{ ($bar['income'] / $maxChart) * 200 }}"
                                    fill="var(--color-primary)"
                                    opacity="0.85"
                                    rx="4"
                                />
                                <rect
                                    x="{{ ($i * (600 / count($chartData))) + 3 }}"
                                    y="{{ 220 - ($bar['expense'] / $maxChart) * 200 - 10 }}"
                                    width="{{ (600 / count($chartData)) - 6 }}"
                                    height="{{ ($bar['expense'] / $maxChart) * 200 }}"
                                    fill="#f43f5e"
                                    opacity="0.85"
                                    rx="4"
                                />
                            </g>
                        @endforeach
                    </svg>
                @else
                    <div class="flex items-center justify-center h-full text-gray-400 dark:text-gray-500 text-sm">
                        {{ __('Sin datos para mostrar') }}
                    </div>
                @endif
                <div class="absolute inset-x-0 bottom-0 flex justify-between text-xs text-gray-400 dark:text-gray-500 pt-2">
                    @foreach($chartData as $bar)
                        <span>{{ $bar['month'] }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Expense Distribution --}}
        <div class="rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm border" style="border-color: var(--color-border)">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                {{ __('Distribución de Gastos') }}
            </h4>
            @if(count($categoryDistribution) > 0)
                <div class="space-y-4">
                    @foreach($categoryDistribution as $cat)
                        <div>
                            <div class="flex items-center justify-between text-sm mb-1.5">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $cat['color'] }}"></span>
                                    <span class="text-gray-700 dark:text-gray-300">{{ $cat['name'] }}</span>
                                </div>
                                <span class="text-gray-500 dark:text-gray-400 font-medium">{{ $cat['percentage'] }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2">
                                <div class="h-2 rounded-full transition-all duration-500" style="width: {{ $cat['percentage'] }}%; background-color: {{ $cat['color'] }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex items-center justify-center h-32 text-gray-400 dark:text-gray-500 text-sm">
                    {{ __('Sin gastos este mes') }}
                </div>
            @endif
        </div>
    </div>

    {{-- Recent Transactions + Budgets --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Recent Transactions --}}
        <div class="lg:col-span-2 rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm border" style="border-color: var(--color-border)">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ __('Transacciones Recientes') }}
                </h4>
                <a href="{{ route('gastos') }}" wire:navigate class="text-sm font-medium hover:underline transition-colors duration-150" style="color: var(--color-primary)">
                    {{ __('Ver todas') }} &rarr;
                </a>
            </div>
            @if(count($recentTransactions) > 0)
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
                            @foreach($recentTransactions as $tx)
                                <tr class="border-b border-gray-100 dark:border-gray-700/50 last:border-0 transition-colors duration-150 hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                    <td class="py-3 text-gray-600 dark:text-gray-400">{{ $tx['date'] }}</td>
                                    <td class="py-3 font-medium text-gray-900 dark:text-gray-200">{{ $tx['description'] }}</td>
                                    <td class="py-3">
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-medium"
                                              style="background-color: {{ $tx['categoryColor'] }}20; color: {{ $tx['categoryColor'] }}">
                                            {{ $tx['category'] }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-right font-medium {{ $tx['type'] === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $tx['formatted'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="flex items-center justify-center h-32 text-gray-400 dark:text-gray-500 text-sm">
                    {{ __('Sin transacciones recientes') }}
                </div>
            @endif
        </div>

        {{-- Budget Progress --}}
        <div class="rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm border" style="border-color: var(--color-border)">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ __('Presupuestos') }}
                </h4>
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('Este mes') }}</span>
            </div>
            @if(count($budgets) > 0)
                <div class="space-y-5">
                    @foreach($budgets as $budget)
                        <div>
                            <div class="flex items-center justify-between text-sm mb-1.5">
                                <span class="text-gray-700 dark:text-gray-300">{{ $budget['name'] }}</span>
                                <span class="text-gray-500 dark:text-gray-400 text-xs">
                                    {{ $budget['spent'] }} / {{ $budget['total'] }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2.5">
                                <div
                                    class="h-2.5 rounded-full transition-all duration-500 {{ $budget['percentage'] > 90 ? 'bg-red-500' : ($budget['percentage'] > 70 ? 'bg-amber-500' : '') }}"
                                    style="width: {{ min($budget['percentage'], 100) }}%; {{ $budget['percentage'] <= 70 ? 'background-color: var(--color-primary)' : '' }}"
                                ></div>
                            </div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $budget['percentage'] }}% utilizado</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex items-center justify-center h-32 text-gray-400 dark:text-gray-500 text-sm">
                    {{ __('Sin presupuestos activos') }}
                </div>
            @endif
        </div>
    </div>
</div>
