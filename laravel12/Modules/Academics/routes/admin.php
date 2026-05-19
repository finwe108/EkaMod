<?php

use Illuminate\Support\Facades\Route;
use Modules\Academics\Http\Controllers\Admin\SubjectController;

/*
|--------------------------------------------------------------------------
| Academic Management Routes
|--------------------------------------------------------------------------
|
| Handles academic configuration such as subjects.
|
| Existing subject URLs and route names are preserved:
| /admin/subjects
| admin.subjects.*
|
*/

Route::resource('subjects', SubjectController::class);