<?php

use Illuminate\Support\Facades\Route;
use Modules\SchoolSettings\Http\Controllers\SchoolSettingController;

/*
|--------------------------------------------------------------------------
| School Settings Routes
|--------------------------------------------------------------------------
|
| Handles administrative editing of school-wide system settings.
|
| Important migration rule:
| These routes are loaded inside the existing admin route group, so they
| preserve the original URL prefix, route names, middleware, and permissions.
|
| Existing URLs:
| /admin/school-settings
|
| Existing route names:
| admin.school-settings.edit
| admin.school-settings.update
|
*/

Route::get('school-settings', [SchoolSettingController::class, 'edit'])
    ->name('school-settings.edit');

Route::put('school-settings', [SchoolSettingController::class, 'update'])
    ->name('school-settings.update');