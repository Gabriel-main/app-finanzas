<?php

namespace App\Livewire;

use App\Models\Transaction;
use Livewire\Component;

class Dashboard extends Component
{
    public float $totalBalance = 0;
    public float $monthlyIncome = 0;
    public float $monthlyExpenses = 0;
    public float $savingsRate = 0;
    public array $chartData = [];
    public float $maxChart = 0;
    public array $categoryDistribution = [];
    public array $recentTransactions = [];
    public array $budgets = [];

    protected $listeners = ['transaction-saved' => '$refresh'];

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        $accountIds = auth()->user()->accounts()->pluck('id');

        $this->totalBalance = (float) auth()->user()->accounts()->sum('balance');

        $this->monthlyIncome = (float) Transaction::whereIn('account_id', $accountIds)
            ->where('type', 'income')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');

        $this->monthlyExpenses = (float) Transaction::whereIn('account_id', $accountIds)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');

        $this->savingsRate = $this->monthlyIncome > 0
            ? round((($this->monthlyIncome - $this->monthlyExpenses) / $this->monthlyIncome) * 100, 1)
            : 0;

        $this->chartData = $this->buildChartData($accountIds);
        $this->maxChart = (float) collect($this->chartData)->flatMap(fn ($d) => [$d['income'], $d['expense']])->max();
        $this->categoryDistribution = $this->buildCategoryDistribution($accountIds);
        $this->recentTransactions = $this->buildRecentTransactions($accountIds);
        $this->budgets = $this->buildBudgets($accountIds);
    }

    private function buildChartData($accountIds): array
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = [
                'month' => $date->format('M'),
                'income' => (float) Transaction::whereIn('account_id', $accountIds)
                    ->where('type', 'income')
                    ->whereYear('transaction_date', $date->year)
                    ->whereMonth('transaction_date', $date->month)
                    ->sum('amount'),
                'expense' => (float) Transaction::whereIn('account_id', $accountIds)
                    ->where('type', 'expense')
                    ->whereYear('transaction_date', $date->year)
                    ->whereMonth('transaction_date', $date->month)
                    ->sum('amount'),
            ];
        }
        return $months;
    }

    private function buildCategoryDistribution($accountIds): array
    {
        $totalExpenses = (float) Transaction::whereIn('account_id', $accountIds)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');

        if ($totalExpenses <= 0) {
            return [];
        }

        return Transaction::whereIn('account_id', $accountIds)
            ->where('transactions.type', 'expense')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, categories.color, sum(transactions.amount) as total')
            ->groupBy('categories.id', 'categories.name', 'categories.color')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => [
                'name' => $row->name,
                'color' => $row->color,
                'percentage' => round(($row->total / $totalExpenses) * 100, 1),
            ])
            ->toArray();
    }

    private function buildRecentTransactions($accountIds): array
    {
        return Transaction::whereIn('account_id', $accountIds)
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->limit(6)
            ->get()
            ->map(fn ($t) => [
                'date' => $t->transaction_date->format('d M'),
                'description' => $t->description ?: __('Sin descripción'),
                'category' => $t->category?->name ?? '—',
                'categoryColor' => $t->category?->color ?? '#6b7280',
                'type' => $t->type,
                'formatted' => ($t->type === 'income' ? '+' : '-') . '$' . number_format($t->amount, 2),
            ])
            ->toArray();
    }

    private function buildBudgets($accountIds): array
    {
        $budgets = auth()->user()->budgets()
            ->with('category')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();

        return $budgets->map(function ($budget) use ($accountIds) {
            $spent = (float) Transaction::whereIn('account_id', $accountIds)
                ->where('category_id', $budget->category_id)
                ->where('transaction_date', '>=', $budget->start_date)
                ->where('transaction_date', '<=', $budget->end_date)
                ->where('type', 'expense')
                ->sum('amount');

            $percentage = $budget->amount_limit > 0
                ? round(($spent / $budget->amount_limit) * 100, 1)
                : 0;

            return [
                'name' => $budget->category?->name ?? '—',
                'spent' => '$' . number_format($spent, 0),
                'total' => '$' . number_format($budget->amount_limit, 0),
                'percentage' => $percentage,
            ];
        })->toArray();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
