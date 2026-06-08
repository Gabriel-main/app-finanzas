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
    private ?\Illuminate\Support\Collection $cachedAccountIds = null;

    protected $listeners = ['transaction-saved' => '$refresh'];

    public function mount(): void
    {
        //
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

    private function getAccountIds(): \Illuminate\Support\Collection
    {
        return $this->cachedAccountIds
            ??= auth()->user()->accounts()->pluck('id');
    }

    public function getTransactionsProperty()
    {
        $query = Transaction::whereIn('account_id', $this->getAccountIds())
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
        return \App\Models\Categories::whereHas('transactions', function ($q) {
            $q->whereIn('account_id', $this->getAccountIds());
        })->get();
    }

    public function deleteTransaction(string $id): void
    {
        try {
            $deleted = Transaction::whereIn('account_id', $this->getAccountIds())
                ->where('id', $id)
                ->delete();

            if ($deleted) {
                $this->dispatch('transaction-deleted');
                $this->dispatch('show-toast', message: 'Transacción eliminada.', type: 'success');
            } else {
                $this->dispatch('show-toast', message: 'Transacción no encontrada.', type: 'error');
            }
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Error al eliminar la transacción.', type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.expense-list', [
            'transactions' => $this->transactions,
            'categories' => $this->categories,
        ]);
    }
}
