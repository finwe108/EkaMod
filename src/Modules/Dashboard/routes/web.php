<?php

use Illuminate\Support\Facades\Route;
use Modules\Dashboard\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Owns the role-aware dashboard entry point and admin dashboard alias.
|
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
        ->middleware(['role:super_admin,admin,hr,registrar,teacher'])
        ->name('admin.dashboard');
});