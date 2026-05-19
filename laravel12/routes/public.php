<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
|
| Handles public-facing pages and public admission application routes.
| These routes do not require authentication.
|
*/

Route::get('/clear-cache-now', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');

    return 'Cache cleared';
});

Route::get('/modules-health-check', function () {
    return \Modules\Core\Support\ModuleHealthCheck::message();
});

