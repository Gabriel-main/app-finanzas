<?php

namespace App\Repositories\Interfaces;

use App\Models\Setting;

interface SettingRepositoryInterface
{
    public function getForUser(int $userId): ?Setting;

    public function createOrUpdate(int $userId, array $data): Setting;

    public function getGlobal(): ?Setting;

    public function updateGlobal(array $data): Setting;

    public function getMergedForUser(int $userId): Setting;
}
