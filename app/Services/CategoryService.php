<?php

namespace App\Services;

use App\Models\Categories;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    public function __construct(
        private readonly CategoryRepositoryInterface $repository,
    ) {}

    public function getUserCategories(int $userId, string $type): Collection
    {
        return $this->repository->getForUser($userId, $type);
    }

    public function createCategory(int $userId, array $data): Categories
    {
        $data['user_id'] = $userId;

        return $this->repository->create($data);
    }

    public function updateCategory(int $userId, int $categoryId, array $data): ?Categories
    {
        $category = $this->repository->findById($categoryId);

        if (! $category || $category->user_id !== $userId) {
            return null;
        }

        return $this->repository->update($categoryId, $data);
    }

    public function deleteCategory(int $userId, int $categoryId): bool
    {
        $category = $this->repository->findById($categoryId);

        if (! $category || $category->user_id !== $userId) {
            return false;
        }

        return $this->repository->delete($categoryId);
    }
}
