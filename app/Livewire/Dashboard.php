<?php

namespace App\Livewire;

use App\Models\Transaction;
use App\Services\SettingService;
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
    public string $chartIncomeColor = '#22c55e';
    public string $chartExpenseColor = '#f43f5e';

    protected $listeners = ['transaction-saved' => '$refresh'];

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        try {
            $settings = app(SettingService::class)->getMergedSettings(auth()->id());
            $this->chartIncomeColor = $settings->chart_income_color;
            $this->chartExpenseColor = $settings->chart_expense_color;

            $accounts = auth()->user()->accounts()->get();
            $accountIds = $accounts->pluck('id');

            $this->totalBalance = (float) $accounts->sum('balance');

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
        } catch (\Exception $e) {
            report($e);
            $this->dispatch('show-toast', message: 'Error al cargar los datos del dashboard.', type: 'error');
        }
    }

    private function buildChartData($accountIds): array
    {
        $startDate = now()->subMonths(5)->startOfMonth();
        $endDate = now()->endOfMonth();

        $rows = Transaction::whereIn('account_id', $accountIds)
            ->where('transaction_date', '>=', $startDate)
            ->where('transaction_date', '<=', $endDate)
            ->selectRaw("
                strftime('%Y-%m', transaction_date) as month_key,
                strftime('%m', transaction_date) as month_num,
                sum(case when type = 'income' then amount else 0 end) as income,
                sum(case when type = 'expense' then amount else 0 end) as expense
            ")
            ->groupBy('month_key', 'month_num')
            ->orderBy('month_key')
            ->get()
            ->keyBy('month_key');

        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $key = $date->format('Y-m');
            $row = $rows->get($key);
            $months[] = [
                'month' => $date->format('M'),
                'income' => (float) ($row->income ?? 0),
                'expense' => (float) ($row->expense ?? 0),
            ];
        }
        return $months;
    }

    private function buildCategoryDistribution($accountIds): array
    {
        $results = Transaction::whereIn('account_id', $accountIds)
            ->where('transactions.type', 'expense')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, categories.color, sum(transactions.amount) as total')
            ->groupBy('categories.id', 'categories.name', 'categories.color')
            ->orderByDesc('total')
            ->get();

        $totalExpenses = (float) $results->sum('total');

        if ($totalExpenses <= 0) {
            return [];
        }

        return $results->map(fn ($row) => [
            'name' => $row->name,
            'color' => $row->color,
            'percentage' => round(($row->total / $totalExpenses) * 100, 1),
        ])->toArray();
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

        if ($budgets->isEmpty()) {
            return [];
        }

        $minDate = $budgets->min('start_date');
        $maxDate = $budgets->max('end_date');

        $spentByCategory = Transaction::whereIn('account_id', $accountIds)
            ->where('type', 'expense')
            ->where('transaction_date', '>=', $minDate)
            ->where('transaction_date', '<=', $maxDate)
            ->whereIn('category_id', $budgets->pluck('category_id'))
            ->selectRaw('category_id, sum(amount) as total_spent')
            ->groupBy('category_id')
            ->pluck('total_spent', 'category_id');

        return $budgets->map(function ($budget) use ($spentByCategory) {
            $spent = (float) ($spentByCategory->get($budget->category_id) ?? 0);
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
