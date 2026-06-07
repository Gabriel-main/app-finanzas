<?php

namespace App\Repositories\Interfaces;

use App\Models\Categories;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface
{
    public function getForUser(int $userId, string $type): Collection;

    public function create(array $data): Categories;

    public function findById(int $id): ?Categories;

    public function delete(int $id): bool;
}
