<?php

use Illuminate\Support\Facades\Route;
use Modules\Sections\Http\Controllers\SectionController;
use Modules\Sections\Http\Controllers\SectionEnrollmentController;

/*
|--------------------------------------------------------------------------
| Section Management Routes
|--------------------------------------------------------------------------
|
| Handles administrative section CRUD operations.
|
| Existing URLs and route names are preserved:
| /admin/sections
| admin.sections.*
|
*/

Route::resource('sections', SectionController::class);

Route::get('sections/{section}/enroll/search', [SectionEnrollmentController::class, 'search'])
    ->name('sections.enroll.search');

Route::post('sections/{section}/enroll/existing', [SectionEnrollmentController::class, 'enrollExisting'])
    ->name('sections.enroll.existing');