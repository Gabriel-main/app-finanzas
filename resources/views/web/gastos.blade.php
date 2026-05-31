<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mis Gastos') }}
        </h2>
    </x-slot>

    <div class="py-6" x-data="expensesData()" x-init="init()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="rounded-2xl bg-white dark:bg-gray-800 p-5 shadow-sm border border-gray-200 dark:border-gray-700">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Gastado') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">$8,320.00</p>
                    <p class="text-xs text-red-500 mt-1">{{ __('+12.4% vs mes anterior') }}</p>
                </div>
                <div class="rounded-2xl bg-white dark:bg-gray-800 p-5 shadow-sm border border-gray-200 dark:border-gray-700">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Categoría Principal') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ __('Vivienda') }}</p>
                    <p class="text-xs text-gray-400 mt-1">$2,912.00 {{ __('este mes') }}</p>
                </div>
                <div class="rounded-2xl bg-white dark:bg-gray-800 p-5 shadow-sm border border-gray-200 dark:border-gray-700">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Promedio Diario') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">$277.33</p>
                    <p class="text-xs text-gray-400 mt-1">{{ __('en 30 días') }}</p>
                </div>
                <div class="rounded-2xl bg-white dark:bg-gray-800 p-5 shadow-sm border border-gray-200 dark:border-gray-700">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Transacciones') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">48</p>
                    <p class="text-xs text-gray-400 mt-1">{{ __('este mes') }}</p>
                </div>
            </div>

            {{-- Filters --}}
            <div class="rounded-2xl bg-white dark:bg-gray-800 p-5 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex flex-wrap items-center gap-3">
                        {{-- Search --}}
                        <div class="relative">
                            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input
                                type="text"
                                x-model="search"
                                placeholder="{{ __('Buscar gasto...') }}"
                                class="pl-10 pr-4 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-56"
                            >
                        </div>

                        {{-- Category Filter --}}
                        <select
                            x-model="categoryFilter"
                            class="px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        >
                            <option value="all">{{ __('Todas las categorías') }}</option>
                            <template x-for="cat in categories" :key="cat">
                                <option :value="cat" x-text="cat"></option>
                            </template>
                        </select>

                        {{-- Date Range --}}
                        <input
                            type="month"
                            x-model="monthFilter"
                            class="px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        >

                        {{-- Type Filter --}}
                        <select
                            x-model="typeFilter"
                            class="px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        >
                            <option value="all">{{ __('Todos') }}</option>
                            <option value="expense">{{ __('Gastos') }}</option>
                            <option value="income">{{ __('Ingresos') }}</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400" x-text="filteredExpenses.length + ' {{ __('resultados') }}'"></span>
                    </div>
                </div>
            </div>

            {{-- Expenses Table --}}
            <div class="rounded-2xl bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                                <th @click="sortBy('date')" class="text-left px-5 py-3.5 font-medium text-gray-500 dark:text-gray-400 cursor-pointer hover:text-gray-700 dark:hover:text-gray-200 transition-colors duration-150">
                                    <div class="flex items-center gap-1">
                                        {{ __('Fecha') }}
                                        <template x-if="sortField === 'date'">
                                            <svg class="w-3.5 h-3.5" :class="sortDir === 'asc' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </template>
                                    </div>
                                </th>
                                <th @click="sortBy('description')" class="text-left px-5 py-3.5 font-medium text-gray-500 dark:text-gray-400 cursor-pointer hover:text-gray-700 dark:hover:text-gray-200 transition-colors duration-150">
                                    <div class="flex items-center gap-1">
                                        {{ __('Descripción') }}
                                        <template x-if="sortField === 'description'">
                                            <svg class="w-3.5 h-3.5" :class="sortDir === 'asc' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </template>
                                    </div>
                                </th>
                                <th @click="sortBy('category')" class="text-left px-5 py-3.5 font-medium text-gray-500 dark:text-gray-400 cursor-pointer hover:text-gray-700 dark:hover:text-gray-200 transition-colors duration-150">
                                    <div class="flex items-center gap-1">
                                        {{ __('Categoría') }}
                                        <template x-if="sortField === 'category'">
                                            <svg class="w-3.5 h-3.5" :class="sortDir === 'asc' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </template>
                                    </div>
                                </th>
                                <th @click="sortBy('type')" class="text-left px-5 py-3.5 font-medium text-gray-500 dark:text-gray-400 cursor-pointer hover:text-gray-700 dark:hover:text-gray-200 transition-colors duration-150">
                                    <div class="flex items-center gap-1">
                                        {{ __('Tipo') }}
                                        <template x-if="sortField === 'type'">
                                            <svg class="w-3.5 h-3.5" :class="sortDir === 'asc' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </template>
                                    </div>
                                </th>
                                <th @click="sortBy('amount')" class="text-right px-5 py-3.5 font-medium text-gray-500 dark:text-gray-400 cursor-pointer hover:text-gray-700 dark:hover:text-gray-200 transition-colors duration-150">
                                    <div class="flex items-center justify-end gap-1">
                                        {{ __('Monto') }}
                                        <template x-if="sortField === 'amount'">
                                            <svg class="w-3.5 h-3.5" :class="sortDir === 'asc' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </template>
                                    </div>
                                </th>
                                <th class="text-right px-5 py-3.5 font-medium text-gray-500 dark:text-gray-400">{{ __('Acción') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(exp, i) in paginatedExpenses" :key="i">
                                <tr class="border-b border-gray-100 dark:border-gray-700/50 last:border-0 transition-colors duration-150 hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                    <td class="px-5 py-4 text-gray-600 dark:text-gray-400 whitespace-nowrap" x-text="exp.date"></td>
                                    <td class="px-5 py-4 font-medium text-gray-900 dark:text-gray-200" x-text="exp.description"></td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium" :class="exp.categoryClass" x-text="exp.category"></span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium" :class="exp.type === 'income' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-rose-50 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400'" x-text="exp.type === 'income' ? '{{ __("Ingreso") }}' : '{{ __("Gasto") }}'"></span>
                                    </td>
                                    <td class="px-5 py-4 text-right font-medium" :class="exp.type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400'" x-text="exp.formatted"></td>
                                    <td class="px-5 py-4 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <button class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                </svg>
                                            </button>
                                            <button class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors duration-150">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="paginatedExpenses.length === 0">
                                <tr>
                                    <td colspan="6" class="px-5 py-12 text-center">
                                        <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('No se encontraron gastos') }}</p>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Mostrando') }}
                        <span class="font-medium text-gray-700 dark:text-gray-300" x-text="((currentPage - 1) * perPage) + 1"></span>
                        {{ __('a') }}
                        <span class="font-medium text-gray-700 dark:text-gray-300" x-text="Math.min(currentPage * perPage, filteredExpenses.length)"></span>
                        {{ __('de') }}
                        <span class="font-medium text-gray-700 dark:text-gray-300" x-text="filteredExpenses.length"></span>
                    </p>
                    <div class="flex items-center gap-1.5">
                        <button
                            @click="prevPage"
                            :disabled="currentPage === 1"
                            class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-150 disabled:opacity-40 disabled:cursor-not-allowed"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>
                        </button>
                        <template x-for="page in totalPages" :key="page">
                            <button
                                @click="goToPage(page)"
                                class="min-w-[32px] h-8 rounded-lg text-sm font-medium transition-colors duration-150"
                                :class="currentPage === page ? 'bg-indigo-600 text-white' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700'"
                                x-text="page"
                            ></button>
                        </template>
                        <button
                            @click="nextPage"
                            :disabled="currentPage === totalPages"
                            class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-150 disabled:opacity-40 disabled:cursor-not-allowed"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <livewire:register-transaction />

    <script>
        function expensesData() {
            return {
                search: '',
                categoryFilter: 'all',
                monthFilter: '2026-05',
                typeFilter: 'all',
                sortField: 'date',
                sortDir: 'desc',
                currentPage: 1,
                perPage: 8,

                categories: ['{{ __("Vivienda") }}', '{{ __("Alimentación") }}', '{{ __("Transporte") }}', '{{ __("Entretenimiento") }}', '{{ __("Salud") }}', '{{ __("Educación") }}', '{{ __("Servicios") }}', '{{ __("Otros") }}'],

                allExpenses: [
                    { date: '30 May 2026', description: '{{ __("Pago de renta") }}', category: '{{ __("Vivienda") }}', categoryClass: 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400', type: 'expense', amount: 1200, formatted: '-$1,200.00' },
                    { date: '30 May 2026', description: '{{ __("Supermercado Costco") }}', category: '{{ __("Alimentación") }}', categoryClass: 'bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400', type: 'expense', amount: 245.60, formatted: '-$245.60' },
                    { date: '29 May 2026', description: '{{ __("Gasolina") }}', category: '{{ __("Transporte") }}', categoryClass: 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400', type: 'expense', amount: 65.00, formatted: '-$65.00' },
                    { date: '28 May 2026', description: '{{ __("Netflix") }}', category: '{{ __("Entretenimiento") }}', categoryClass: 'bg-rose-50 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400', type: 'expense', amount: 15.99, formatted: '-$15.99' },
                    { date: '28 May 2026', description: '{{ __("Consulta médica") }}', category: '{{ __("Salud") }}', categoryClass: 'bg-purple-50 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400', type: 'expense', amount: 80.00, formatted: '-$80.00' },
                    { date: '27 May 2026', description: '{{ __("Curso Laravel") }}', category: '{{ __("Educación") }}', categoryClass: 'bg-cyan-50 text-cyan-600 dark:bg-cyan-900/30 dark:text-cyan-400', type: 'expense', amount: 199.99, formatted: '-$199.99' },
                    { date: '27 May 2026', description: '{{ __("Electricidad") }}', category: '{{ __("Vivienda") }}', categoryClass: 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400', type: 'expense', amount: 95.00, formatted: '-$95.00' },
                    { date: '26 May 2026', description: '{{ __("Agua") }}', category: '{{ __("Vivienda") }}', categoryClass: 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400', type: 'expense', amount: 45.00, formatted: '-$45.00' },
                    { date: '26 May 2026', description: '{{ __("Internet") }}', category: '{{ __("Servicios") }}', categoryClass: 'bg-sky-50 text-sky-600 dark:bg-sky-900/30 dark:text-sky-400', type: 'expense', amount: 59.99, formatted: '-$59.99' },
                    { date: '25 May 2026', description: '{{ __("Cena restaurante") }}', category: '{{ __("Alimentación") }}', categoryClass: 'bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400', type: 'expense', amount: 78.50, formatted: '-$78.50' },
                    { date: '25 May 2026', description: '{{ __("Uber") }}', category: '{{ __("Transporte") }}', categoryClass: 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400', type: 'expense', amount: 22.30, formatted: '-$22.30' },
                    { date: '24 May 2026', description: '{{ __("Farmacia") }}', category: '{{ __("Salud") }}', categoryClass: 'bg-purple-50 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400', type: 'expense', amount: 34.50, formatted: '-$34.50' },
                    { date: '24 May 2026', description: '{{ __("Spotify") }}', category: '{{ __("Entretenimiento") }}', categoryClass: 'bg-rose-50 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400', type: 'expense', amount: 9.99, formatted: '-$9.99' },
                    { date: '23 May 2026', description: '{{ __("Libros") }}', category: '{{ __("Educación") }}', categoryClass: 'bg-cyan-50 text-cyan-600 dark:bg-cyan-900/30 dark:text-cyan-400', type: 'expense', amount: 42.00, formatted: '-$42.00' },
                    { date: '23 May 2026', description: '{{ __("Mantenimiento auto") }}', category: '{{ __("Transporte") }}', categoryClass: 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400', type: 'expense', amount: 180.00, formatted: '-$180.00' },
                    { date: '22 May 2026', description: '{{ __("Seguro médico") }}', category: '{{ __("Salud") }}', categoryClass: 'bg-purple-50 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400', type: 'expense', amount: 350.00, formatted: '-$350.00' },
                    { date: '22 May 2026', description: '{{ __("Teléfono") }}', category: '{{ __("Servicios") }}', categoryClass: 'bg-sky-50 text-sky-600 dark:bg-sky-900/30 dark:text-sky-400', type: 'expense', amount: 39.99, formatted: '-$39.99' },
                    { date: '21 May 2026', description: '{{ __("Freelance project") }}', category: '{{ __("Ingreso") }}', categoryClass: 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400', type: 'income', amount: 1500, formatted: '+$1,500.00' },
                ],

                get filteredExpenses() {
                    let result = [...this.allExpenses];

                    if (this.search) {
                        const q = this.search.toLowerCase();
                        result = result.filter(e => e.description.toLowerCase().includes(q));
                    }

                    if (this.categoryFilter !== 'all') {
                        result = result.filter(e => e.category === this.categoryFilter);
                    }

                    if (this.typeFilter !== 'all') {
                        result = result.filter(e => e.type === this.typeFilter);
                    }

                    result.sort((a, b) => {
                        let valA, valB;
                        if (this.sortField === 'amount') {
                            valA = a.amount;
                            valB = b.amount;
                        } else if (this.sortField === 'date') {
                            valA = new Date(a.date);
                            valB = new Date(b.date);
                        } else {
                            valA = a[this.sortField].toLowerCase();
                            valB = b[this.sortField].toLowerCase();
                        }
                        if (valA < valB) return this.sortDir === 'asc' ? -1 : 1;
                        if (valA > valB) return this.sortDir === 'asc' ? 1 : -1;
                        return 0;
                    });

                    return result;
                },

                get totalPages() {
                    return Math.ceil(this.filteredExpenses.length / this.perPage);
                },

                get paginatedExpenses() {
                    const start = (this.currentPage - 1) * this.perPage;
                    return this.filteredExpenses.slice(start, start + this.perPage);
                },

                sortBy(field) {
                    if (this.sortField === field) {
                        this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
                    } else {
                        this.sortField = field;
                        this.sortDir = 'desc';
                    }
                },

                prevPage() {
                    if (this.currentPage > 1) this.currentPage--;
                },

                nextPage() {
                    if (this.currentPage < this.totalPages) this.currentPage++;
                },

                goToPage(page) {
                    this.currentPage = page;
                },

                init() {
                    // Reset to page 1 when filters change
                    this.$watch('search', () => this.currentPage = 1);
                    this.$watch('categoryFilter', () => this.currentPage = 1);
                    this.$watch('typeFilter', () => this.currentPage = 1);
                }
            }
        }
    </script>
</x-app-layout>
