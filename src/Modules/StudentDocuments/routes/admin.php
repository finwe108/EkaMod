<?php

use Illuminate\Support\Facades\Route;
use Modules\StudentDocuments\Http\Controllers\StudentDocumentVerificationController;

/*
|--------------------------------------------------------------------------
| Student Document Verification Routes
|--------------------------------------------------------------------------
|
| Preserves existing admin student document verification URLs and names.
|
*/

Route::get('student-documents', [StudentDocumentVerificationController::class, 'index'])
    ->name('student-documents.index');

Route::put('student-documents/{studentDocument}/verify', [StudentDocumentVerificationController::class, 'verify'])
    ->name('student-documents.verify');

Route::put('student-documents/{studentDocument}/reject', [StudentDocumentVerificationController::class, 'reject'])
    ->name('student-documents.reject');