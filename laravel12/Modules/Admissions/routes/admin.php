<?php

use Illuminate\Support\Facades\Route;
use Modules\Admissions\Http\Controllers\Admin\AdmissionApplicationReviewController;

/*
|--------------------------------------------------------------------------
| Admin Admission Review Routes
|--------------------------------------------------------------------------
|
| Handles administrative review, acceptance, and rejection of admission
| applications.
|
| Existing URLs and route names are preserved.
|
*/

Route::get('/admission-applications', [AdmissionApplicationReviewController::class, 'index'])
    ->name('admission_applications.index');

Route::get('/admission-applications/{admissionApplication}', [AdmissionApplicationReviewController::class, 'show'])
    ->name('admission_applications.show');

Route::post('/admission-applications/{admissionApplication}/review', [AdmissionApplicationReviewController::class, 'markUnderReview'])
    ->name('admission_applications.review');

Route::post('/admission-applications/{admissionApplication}/accept', [AdmissionApplicationReviewController::class, 'accept'])
    ->name('admission_applications.accept');

Route::post('/admission-applications/{admissionApplication}/reject', [AdmissionApplicationReviewController::class, 'reject'])
    ->name('admission_applications.reject');