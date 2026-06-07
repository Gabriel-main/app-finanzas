<?php

namespace App\Repositories\Interfaces;

use App\Models\Setting;

interface SettingRepositoryInterface
{
    public function getForUser(int $userId): ?Setting;

    public function createOrUpdate(int $userId, array $data): Setting;
}
