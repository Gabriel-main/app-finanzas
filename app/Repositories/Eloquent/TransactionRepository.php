<?php

namespace App\Repositories\Eloquent;

use App\Models\Transaction;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function __construct(
        private readonly Transaction $model,
    ) {}

    public function getForUser(int $userId, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model
            ->where('account_id', $userId)
            ->with(['category', 'account']);

        if (! empty($filters['search'])) {
            $query->where('description', 'like', "%{$filters['search']}%");
        }

        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (! empty($filters['date_from'])) {
            $query->where('transaction_date', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->where('transaction_date', '<=', $filters['date_to']);
        }

        $sortField = $filters['sort_field'] ?? 'transaction_date';
        $sortDirection = $filters['sort_direction'] ?? 'desc';

        return $query->orderBy($sortField, $sortDirection)
            ->paginate($filters['per_page'] ?? 15);
    }

    public function create(array $data): Transaction
    {
        return $this->model->create($data);
    }

    public function update(string $id, array $data): ?Transaction
    {
        $transaction = $this->findById($id);
        if (! $transaction) {
            return null;
        }
        $transaction->update($data);
        return $transaction;
    }

    public function findById(string $id): ?Transaction
    {
        return $this->model->with(['category', 'account'])->find($id);
    }

    public function delete(string $id): bool
    {
        $transaction = $this->findById($id);
        return $transaction ? $transaction->delete() : false;
    }

    public function getTotalByType(int $userId, string $type, ?string $startDate = null, ?string $endDate = null): float
    {
        $query = $this->model
            ->where('account_id', $userId)
            ->where('type', $type);

        if ($startDate) {
            $query->where('transaction_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('transaction_date', '<=', $endDate);
        }

        return (float) $query->sum('amount');
    }
}
