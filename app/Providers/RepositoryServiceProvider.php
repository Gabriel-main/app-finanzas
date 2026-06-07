<?php

namespace App\Providers;

use App\Models\Categories;
use App\Models\Setting;
use App\Models\Transaction;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\SettingRepository;
use App\Repositories\Eloquent\TransactionRepository;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\SettingRepositoryInterface;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            CategoryRepositoryInterface::class,
            fn () => new CategoryRepository(new Categories),
        );

        $this->app->bind(
            TransactionRepositoryInterface::class,
            fn () => new TransactionRepository(new Transaction),
        );

        $this->app->bind(
            SettingRepositoryInterface::class,
            fn () => new SettingRepository(new Setting),
        );
    }

    public function boot(): void {}
}
