<?php

// use App\Http\Controllers\Admin\TeacherScheduleController;
// use App\Http\Controllers\Teacher\DashboardController;
// use App\Http\Controllers\Teacher\GradeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Teacher Routes
|--------------------------------------------------------------------------
|
| Handles teacher-facing pages. Existing route names, URLs, middleware,
| and controller references are preserved.
|
*/

Route::middleware(['auth', 'role:teacher,super_admin,admin,hr'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {


        /*
         * This route currently resolves to /teacher/teacher/schedule.
         * It is preserved exactly during Phase 1 to avoid breaking links.
         */

    });