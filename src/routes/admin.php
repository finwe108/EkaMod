<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin / HR / Super Admin Routes
|--------------------------------------------------------------------------
|
| Handles administrative modules. Existing middleware, prefixes, route names,
| URLs, and controller references are preserved.
|
*/

Route::middleware(['auth', 'role:super_admin,admin,hr'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    });

/*
|--------------------------------------------------------------------------
| Registrar Routes
|--------------------------------------------------------------------------
|
| Reserved for registrar-specific pages.
|
*/

Route::middleware(['auth', 'role:registrar,super_admin,admin'])
    ->prefix('registrar')
    ->name('registrar.')
    ->group(function () {
        //
    });

/*
|--------------------------------------------------------------------------
| Finance Module - Future TODO
|--------------------------------------------------------------------------
|
| Finance is not implemented yet.
|
| Do not register placeholder routes here because they can create broken
| navigation, missing controller errors, or misleading route availability.
|
| Planned future scope:
| - fee setup
| - student balances
| - payments
| - receipts
| - online payment integration
| - fee reminders
|
*/