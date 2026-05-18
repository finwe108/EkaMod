<?php

use Illuminate\Support\Facades\Route;
use Modules\Students\Http\Controllers\Admin\StudentController;
use Modules\Students\Http\Controllers\Admin\StudentCredentialController;

/*
|--------------------------------------------------------------------------
| Admin Student Credential Routes
|--------------------------------------------------------------------------
|
| Handles administrative student login credential management.
|
| Existing route names and URLs are preserved.
|
*/

Route::resource('students', StudentController::class);

Route::get('/admin/students/sections/{schoolYearId}', [StudentController::class, 'getSectionsBySchoolYear'])
    ->name('students.sections');

Route::get('students/{student}/credentials', [StudentCredentialController::class, 'edit'])
    ->name('students.credentials.edit');

Route::put('students/{student}/credentials', [StudentCredentialController::class, 'update'])
    ->name('students.credentials.update');

Route::post('students/{student}/credentials/create', [StudentCredentialController::class, 'store'])
    ->name('students.credentials.store');