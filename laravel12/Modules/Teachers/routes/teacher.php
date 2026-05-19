<?php

use Illuminate\Support\Facades\Route;
use Modules\Teachers\Http\Controllers\DashboardController;
use Modules\Teachers\Http\Controllers\GradeController;

/*
|--------------------------------------------------------------------------
| Teacher Portal Routes
|--------------------------------------------------------------------------
|
| Handles teacher-facing pages while preserving the existing route names,
| URLs, middleware, and permissions from the original monolith.
|
*/

Route::get('/classes', [DashboardController::class, 'classes'])
    ->name('classes');

Route::get('/grades', [DashboardController::class, 'grades'])
    ->name('grades');

Route::get('/attendance', [DashboardController::class, 'attendance'])
    ->name('attendance');

Route::get('/loads/{teacherLoad}/grades', [GradeController::class, 'index'])
    ->name('loads.grades.index');

Route::post('/loads/{teacherLoad}/grades', [GradeController::class, 'store'])
    ->name('loads.grades.store');

/*
|--------------------------------------------------------------------------
| Teacher Schedule
|--------------------------------------------------------------------------
|
| Teacher self-service schedule page.
|
| NOTE:
| The old legacy route was:
| /teacher/teacher/schedule
|
| It pointed to a missing controller method and non-existent view.
| This route safely replaces the broken implementation.
|
*/

Route::get('/schedule', [DashboardController::class, 'schedule'])
    ->name('schedule');