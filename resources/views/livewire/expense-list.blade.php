<div>
    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="rounded-2xl bg-white dark:bg-gray-800 p-5 shadow-sm border border-gray-200 dark:border-gray-700">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Gastado') }}</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                ${{ number_format($transactions->sum(fn($t) => $t->type === 'expense' ? $t->amount : 0), 2) }}
            </p>
        </div>
        <div class="rounded-2xl bg-white dark:bg-gray-800 p-5 shadow-sm border border-gray-200 dark:border-gray-700">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Ingresos') }}</p>
            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">
                +${{ number_format($transactions->sum(fn($t) => $t->type === 'income' ? $t->amount : 0), 2) }}
            </p>
        </div>
        <div class="rounded-2xl bg-white dark:bg-gray-800 p-5 shadow-sm border border-gray-200 dark:border-gray-700">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Transacciones') }}</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $transactions->total() }}</p>
        </div>
        <div class="rounded-2xl bg-white dark:bg-gray-800 p-5 shadow-sm border border-gray-200 dark:border-gray-700">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Categorías') }}</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $categories->count() }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="rounded-2xl bg-white dark:bg-gray-800 p-5 shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-3">
                <div class="relative">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input
                        type="text"
                        wire:model.live="search"
                        placeholder="{{ __('Buscar...') }}"
                        class="pl-10 pr-4 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-56"
                    >
                </div>

                <select wire:model.live="categoryFilter" class="px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="all">{{ __('Todas las categorías') }}</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>

                <input
                    type="month"
                    wire:model.live="monthFilter"
                    class="px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                >

                <select wire:model.live="typeFilter" class="px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="all">{{ __('Todos') }}</option>
                    <option value="expense">{{ __('Gastos') }}</option>
                    <option value="income">{{ __('Ingresos') }}</option>
                </select>
            </div>

            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $transactions->total() }} {{ __('resultados') }}</span>
        </div>
    </div>

    {{-- Transactions Table --}}
    <div class="rounded-2xl bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                        <th wire:click="sortBy('transaction_date')" class="text-left px-5 py-3.5 font-medium text-gray-500 dark:text-gray-400 cursor-pointer hover:text-gray-700 dark:hover:text-gray-200 transition-colors duration-150">
                            <div class="flex items-center gap-1">
                                {{ __('Fecha') }}
                                @if($sortField === 'transaction_date')
                                    <svg class="w-3.5 h-3.5 {{ $sortDirection === 'asc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th wire:click="sortBy('description')" class="text-left px-5 py-3.5 font-medium text-gray-500 dark:text-gray-400 cursor-pointer hover:text-gray-700 dark:hover:text-gray-200 transition-colors duration-150">
                            <div class="flex items-center gap-1">
                                {{ __('Descripción') }}
                                @if($sortField === 'description')
                                    <svg class="w-3.5 h-3.5 {{ $sortDirection === 'asc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="text-left px-5 py-3.5 font-medium text-gray-500 dark:text-gray-400">{{ __('Categoría') }}</th>
                        <th wire:click="sortBy('type')" class="text-left px-5 py-3.5 font-medium text-gray-500 dark:text-gray-400 cursor-pointer hover:text-gray-700 dark:hover:text-gray-200 transition-colors duration-150">
                            <div class="flex items-center gap-1">
                                {{ __('Tipo') }}
                                @if($sortField === 'type')
                                    <svg class="w-3.5 h-3.5 {{ $sortDirection === 'asc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th wire:click="sortBy('amount')" class="text-right px-5 py-3.5 font-medium text-gray-500 dark:text-gray-400 cursor-pointer hover:text-gray-700 dark:hover:text-gray-200 transition-colors duration-150">
                            <div class="flex items-center justify-end gap-1">
                                {{ __('Monto') }}
                                @if($sortField === 'amount')
                                    <svg class="w-3.5 h-3.5 {{ $sortDirection === 'asc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="text-right px-5 py-3.5 font-medium text-gray-500 dark:text-gray-400">{{ __('Acción') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr class="border-b border-gray-100 dark:border-gray-700/50 last:border-0 transition-colors duration-150 hover:bg-gray-50 dark:hover:bg-gray-700/30">
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-400 whitespace-nowrap">
                                {{ $transaction->transaction_date->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4 font-medium text-gray-900 dark:text-gray-200">
                                {{ $transaction->description ?: __('Sin descripción') }}
                            </td>
                            <td class="px-5 py-4">
                                @if($transaction->category)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium"
                                          style="background-color: {{ $transaction->category->color }}20; color: {{ $transaction->category->color }}">
                                        {{ $transaction->category->name }}
                                    </span>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium {{ $transaction->type === 'income' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-rose-50 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400' }}">
                                    {{ $transaction->type === 'income' ? __('Ingreso') : __('Gasto') }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right font-medium {{ $transaction->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $transaction->type === 'income' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button wire:click="deleteTransaction('{{ $transaction->id }}')"
                                            wire:confirm="¿Eliminar esta transacción?"
                                            class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors duration-150">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('No se encontraron transacciones') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($transactions->hasPages())
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Mostrando') }}
                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $transactions->firstItem() }}</span>
                    {{ __('a') }}
                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $transactions->lastItem() }}</span>
                    {{ __('de') }}
                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $transactions->total() }}</span>
                </p>
                <div>
                    {{ $transactions->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
