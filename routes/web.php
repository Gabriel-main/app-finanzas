<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::post('logout', function () {
    app(\App\Livewire\Actions\Logout::class)();
    return redirect('/');
})->name('logout');

require __DIR__.'/auth.php';
