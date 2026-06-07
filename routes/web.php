<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'web.welcome');

Route::view('dashboard', 'web.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('gastos', 'web.gastos')
    ->middleware(['auth', 'verified'])
    ->name('gastos');

Route::view('profile', 'web.profile')
    ->middleware(['auth'])
    ->name('profile');

Route::post('logout', function () {
    app(\App\Livewire\Actions\Logout::class)();
    return redirect('/');
})->name('logout');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::apiResource('categories', CategoryController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    Route::apiResource('transactions', TransactionController::class)
        ->only(['index', 'store', 'show', 'update', 'destroy']);
});

require __DIR__.'/auth.php';
