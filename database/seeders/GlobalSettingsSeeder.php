<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class GlobalSettingsSeeder extends Seeder
{
    public function run(): void
    {
        Setting::updateOrCreate(
            ['user_id' => null],
            [
                'app_name' => 'App Finanzas',
                'primary_color' => '#6366f1',
                'chart_income_color' => '#22c55e',
                'chart_expense_color' => '#f43f5e',
            ]
        );
    }
}
