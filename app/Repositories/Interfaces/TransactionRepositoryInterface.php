<?php

namespace App\Repositories\Interfaces;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface TransactionRepositoryInterface
{
    public function getForUser(int $userId, array $filters = []): LengthAwarePaginator;

    public function create(array $data): Transaction;

    public function update(string $id, array $data): ?Transaction;

    public function findById(string $id): ?Transaction;

    public function delete(string $id): bool;

    public function getTotalByType(int $userId, string $type, ?string $startDate = null, ?string $endDate = null): float;
}
