<?php

use Illuminate\Support\Facades\Route;
use Modules\Enrollments\Http\Controllers\EnrollmentController;

/*
|--------------------------------------------------------------------------
| Enrollment Management Routes
|--------------------------------------------------------------------------
|
| Handles enrollment CRUD, enrollment status updates, and section lookup.
|
| SF1 report routes are intentionally not included here yet.
|
*/

Route::resource('enrollments', EnrollmentController::class);

Route::patch('/enrollments/{enrollment}/status', [EnrollmentController::class, 'updateStatus'])
    ->name('enrollments.status.update');

Route::get('ajax/sections-by-school-year', [EnrollmentController::class, 'sectionsBySchoolYear'])
    ->name('ajax.sections-by-school-year');