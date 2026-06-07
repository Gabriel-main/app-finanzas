<?php

namespace App\Services;

use App\Models\Setting;
use App\Repositories\Interfaces\SettingRepositoryInterface;

class SettingService
{
    public function __construct(
        private readonly SettingRepositoryInterface $repository,
    ) {}

    public function getUserSettings(int $userId): Setting
    {
        return $this->repository->getForUser($userId);
    }

    public function updateSettings(int $userId, array $data): Setting
    {
        return $this->repository->createOrUpdate($userId, $data);
    }
}
