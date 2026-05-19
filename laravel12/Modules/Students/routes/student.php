<?php

use Illuminate\Support\Facades\Route;
use Modules\Students\Http\Controllers\StudentAccountController;
use Modules\Students\Http\Controllers\StudentDashboardController;
use Modules\Students\Http\Controllers\StudentDocumentController;
use Modules\Students\Http\Controllers\StudentPasswordController;
use Modules\Students\Http\Controllers\StudentProfileController;

/*
|--------------------------------------------------------------------------
| Student Portal Routes
|--------------------------------------------------------------------------
|
| Handles student-facing dashboard, profile, account, password, and document
| upload routes.
|
| Existing URLs and route names are preserved.
|
*/

Route::get('/dashboard', [StudentDashboardController::class, 'index'])
    ->name('dashboard');

Route::get('/profile', [StudentProfileController::class, 'show'])
    ->name('profile.show');

Route::get('/profile/edit', [StudentProfileController::class, 'edit'])
    ->name('profile.edit');

Route::put('/profile', [StudentProfileController::class, 'update'])
    ->name('profile.update');

Route::get('/change-password', [StudentPasswordController::class, 'edit'])
    ->name('password.edit');

Route::put('/change-password', [StudentPasswordController::class, 'update'])
    ->name('password.update');

Route::get('/documents', [StudentDocumentController::class, 'index'])
    ->name('documents.index');

Route::post('/documents/{documentRequirementRule}/upload', [StudentDocumentController::class, 'upload'])
    ->name('documents.upload');

Route::get('/account', [StudentAccountController::class, 'edit'])
    ->name('account.edit');

Route::put('/account', [StudentAccountController::class, 'update'])
    ->name('account.update');