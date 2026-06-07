<?php

namespace App\Repositories\Eloquent;

use App\Models\Categories;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(
        private readonly Categories $model,
    ) {}

    public function getForUser(int $userId, string $type): Collection
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('type', $type)
            ->orderBy('name')
            ->get();
    }

    public function create(array $data): Categories
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): ?Categories
    {
        $category = $this->findById($id);
        if (! $category) {
            return null;
        }
        $category->update($data);
        return $category;
    }

    public function findById(int $id): ?Categories
    {
        return $this->model->find($id);
    }

    public function delete(int $id): bool
    {
        $category = $this->findById($id);

        return $category ? $category->delete() : false;
    }
}
