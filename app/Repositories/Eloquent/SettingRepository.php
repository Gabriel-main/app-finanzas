<?php

namespace App\Repositories\Eloquent;

use App\Models\Setting;
use App\Repositories\Interfaces\SettingRepositoryInterface;

class SettingRepository implements SettingRepositoryInterface
{
    private const DEFAULTS = [
        'app_name' => 'App Finanzas',
        'primary_color' => '#6366f1',
        'chart_income_color' => '#22c55e',
        'chart_expense_color' => '#f43f5e',
    ];

    public function __construct(
        private readonly Setting $model,
    ) {}

    public function getForUser(int $userId): ?Setting
    {
        return $this->model->firstOrCreate(
            ['user_id' => $userId],
            self::DEFAULTS
        );
    }

    public function createOrUpdate(int $userId, array $data): Setting
    {
        return $this->model->updateOrCreate(
            ['user_id' => $userId],
            $data
        );
    }

    public function getGlobal(): ?Setting
    {
        return $this->model->whereNull('user_id')->first();
    }

    public function updateGlobal(array $data): Setting
    {
        return $this->model->updateOrCreate(
            ['user_id' => null],
            $data
        );
    }

    public function getMergedForUser(int $userId): Setting
    {
        $global = $this->getGlobal();
        $user = $this->model->where('user_id', $userId)->first();

        $merged = self::DEFAULTS;

        if ($global) {
            $merged = array_merge($merged, array_filter([
                'app_name' => $global->app_name,
                'primary_color' => $global->primary_color,
                'logo_path' => $global->logo_path,
            ], fn ($v) => $v !== null));
        }

        if ($user) {
            $merged = array_merge($merged, array_filter([
                'app_name' => $user->app_name,
                'primary_color' => $user->primary_color,
                'chart_income_color' => $user->chart_income_color,
                'chart_expense_color' => $user->chart_expense_color,
                'logo_path' => $user->logo_path,
            ], fn ($v) => $v !== null));
        }

        return (new Setting)->forceFill($merged);
    }
}
