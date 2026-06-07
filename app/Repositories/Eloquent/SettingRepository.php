<?php

namespace App\Repositories\Eloquent;

use App\Models\Setting;
use App\Repositories\Interfaces\SettingRepositoryInterface;

class SettingRepository implements SettingRepositoryInterface
{
    public function __construct(
        private readonly Setting $model,
    ) {}

    public function getForUser(int $userId): ?Setting
    {
        return $this->model->firstOrCreate(
            ['user_id' => $userId],
            ['app_name' => 'App Finanzas', 'primary_color' => '#6366f1']
        );
    }

    public function createOrUpdate(int $userId, array $data): Setting
    {
        return $this->model->updateOrCreate(
            ['user_id' => $userId],
            $data
        );
    }
}
