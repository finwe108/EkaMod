<?php

// use App\Http\Controllers\Student\StudentAccountController;
// use App\Http\Controllers\Student\StudentDashboardController;
// use App\Http\Controllers\Student\StudentDocumentController;
// use App\Http\Controllers\Student\StudentPasswordController;
// use App\Http\Controllers\Student\StudentProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
|
| Handles student portal pages. Existing middleware is preserved as auth-only.
| Role restrictions can be reviewed later as a separate safe change.
|
*/

Route::middleware(['auth'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {



    });