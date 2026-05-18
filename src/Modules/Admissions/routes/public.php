<?php

use Illuminate\Support\Facades\Route;
use Modules\Admissions\Http\Controllers\Public\AdmissionApplicationController;

/*
|--------------------------------------------------------------------------
| Public Admission Routes
|--------------------------------------------------------------------------
|
| Handles public admission application submission.
|
| Existing URLs and route names are preserved.
|
*/

Route::get('/admission/apply', [AdmissionApplicationController::class, 'create'])
    ->name('public.admission.create');

Route::post('/admission/apply', [AdmissionApplicationController::class, 'store'])
    ->name('public.admission.store');

Route::get('/admission/success/{application}', [AdmissionApplicationController::class, 'success'])
    ->name('public.admission.success');