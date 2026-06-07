<?php

namespace App\Providers;

use App\Models\Categories;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            CategoryRepositoryInterface::class,
            fn () => new CategoryRepository(new Categories),
        );
    }

    public function boot(): void
    {
        //
    }
}
