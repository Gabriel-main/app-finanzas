<?php

namespace App\Livewire;

use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

class ExpenseList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $categoryFilter = 'all';
    public string $typeFilter = 'all';
    public string $monthFilter = '';
    public string $sortField = 'transaction_date';
    public string $sortDirection = 'desc';
    protected int $perPage = 10;

    protected $listeners = ['transaction-saved' => '$refresh'];

    public function mount(): void
    {
        $this->monthFilter = now()->format('Y-m');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter(): void
    {
        $this->resetPage();
    }

    public function updatedTypeFilter(): void
    {
        $this->resetPage();
    }

    public function updatedMonthFilter(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'desc';
        }
    }

    public function getTransactionsProperty()
    {
        $user = auth()->user();
        $accountIds = $user->accounts()->pluck('id');

        $query = Transaction::whereIn('account_id', $accountIds)
            ->with(['category', 'account']);

        if ($this->search) {
            $query->where('description', 'like', "%{$this->search}%");
        }

        if ($this->categoryFilter !== 'all') {
            $query->where('category_id', $this->categoryFilter);
        }

        if ($this->typeFilter !== 'all') {
            $query->where('type', $this->typeFilter);
        }

        if ($this->monthFilter) {
            $query->whereYear('transaction_date', substr($this->monthFilter, 0, 4))
                ->whereMonth('transaction_date', substr($this->monthFilter, 5, 2));
        }

        return $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function getCategoriesProperty()
    {
        $user = auth()->user();
        $accountIds = $user->accounts()->pluck('id');

        return \App\Models\Categories::whereHas('transactions', function ($q) use ($accountIds) {
            $q->whereIn('account_id', $accountIds);
        })->get();
    }

    public function deleteTransaction(string $id): void
    {
        $user = auth()->user();
        $accountIds = $user->accounts()->pluck('id');

        Transaction::whereIn('account_id', $accountIds)
            ->where('id', $id)
            ->delete();

        $this->dispatch('transaction-deleted');
    }

    public function render()
    {
        return view('livewire.expense-list', [
            'transactions' => $this->transactions,
            'categories' => $this->categories,
        ]);
    }
}
