<?php

use Illuminate\Support\Facades\Route;
use Modules\SchoolYears\Http\Controllers\SchoolYearController;

/*
|--------------------------------------------------------------------------
| School Year Management Routes
|--------------------------------------------------------------------------
|
| Preserves existing school year URLs and route names.
|
*/

Route::resource('school_years', SchoolYearController::class);