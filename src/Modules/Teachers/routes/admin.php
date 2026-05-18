<?php

use Illuminate\Support\Facades\Route;
use Modules\Teachers\Http\Controllers\Admin\TeacherController;
use Modules\Teachers\Http\Controllers\Admin\TeacherLoadController;
use Modules\Teachers\Http\Controllers\Admin\TeacherScheduleController;

/*
|--------------------------------------------------------------------------
| Admin Teacher Load Routes
|--------------------------------------------------------------------------
|
| Handles administrative teacher load assignment and scheduling.
|
| Existing URLs and route names are preserved:
| /admin/teacher_loads
| admin.teacher_loads.*
|
*/
Route::resource('teachers', TeacherController::class);
Route::resource('teacher_loads', TeacherLoadController::class);
Route::get('/teachers/{teacher}/schedule', [TeacherScheduleController::class, 'show'])
    ->name('teachers.schedule');