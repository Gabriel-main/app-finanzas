<?php

namespace App\Services;

use App\Models\Transaction;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Exception;

class TransactionService
{
    public function __construct(
        private readonly TransactionRepositoryInterface $repository,
    ) {}

    public function getUserTransactions(int $userId, array $filters = []): LengthAwarePaginator
    {
        return $this->repository->getForUser($userId, $filters);
    }

    public function createTransaction(array $data): Transaction
    {
        DB::beginTransaction();

        try {
            $transaction = $this->repository->create($data);
            DB::commit();

            return $transaction;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateTransaction(string $id, array $data): ?Transaction
    {
        DB::beginTransaction();

        try {
            $transaction = $this->repository->update($id, $data);
            DB::commit();

            return $transaction;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getTransaction(string $id): ?Transaction
    {
        return $this->repository->findById($id);
    }

    public function deleteTransaction(string $id): bool
    {
        return $this->repository->delete($id);
    }

    public function getTotalByType(int $userId, string $type, ?string $startDate = null, ?string $endDate = null): float
    {
        return $this->repository->getTotalByType($userId, $type, $startDate, $endDate);
    }
}
