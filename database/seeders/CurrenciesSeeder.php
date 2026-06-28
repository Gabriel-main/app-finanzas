<?php

namespace Database\Seeders;

use App\Models\Currencies;
use Illuminate\Database\Seeder;

class CurrenciesSeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            ["name" => "Dólar", "symbol" => '$'],
            ["name" => "Euro", "symbol" => "€"],
            ["name" => "VES", "symbol" => "Bs"],
        ];

        foreach ($currencies as $currency) {
            Currencies::firstOrCreate(
                ["name" => $currency["name"]],
                ["symbol" => $currency["symbol"]],
            );
        }
    }
}
