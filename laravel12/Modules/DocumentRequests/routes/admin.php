<?php

use Illuminate\Support\Facades\Route;
use Modules\DocumentRequests\Http\Controllers\DocumentRequestController;

Route::get(
    'document-requests',
    [DocumentRequestController::class, 'index']
)->name('document-requests.index');

Route::get(
    'document-requests/create',
    [DocumentRequestController::class, 'create']
)->name('document-requests.create');

Route::post(
    'document-requests',
    [DocumentRequestController::class, 'store']
)->name('document-requests.store');

Route::get(
    'document-requests/{documentRequest}',
    [DocumentRequestController::class, 'show']
)->name('document-requests.show');

# Document Request Admin Routes

/*
|--------------------------------------------------------------------------
| Document Request Lifecycle
|--------------------------------------------------------------------------
*/

Route::post(
  
  'document-requests/{documentRequest}/start-verification',
    [DocumentRequestController::class, 'startVerification']
)->name('document-requests.start-verification');

Route::post(
    'document-requests/{documentRequest}/verify',
    [DocumentRequestController::class, 'verify']
)->name('document-requests.verify');

Route::post(
    'document-requests/{documentRequest}/start-processing',
    [DocumentRequestController::class, 'startProcessing']
)->name('document-requests.start-processing');

Route::post(
    'document-requests/{documentRequest}/ready-for-release',
    [DocumentRequestController::class, 'markReadyForRelease']
)->name('document-requests.ready-for-release');

Route::post(
    'document-requests/{documentRequest}/release',
    [DocumentRequestController::class, 'release']
)->name('document-requests.release');

Route::post(
    'document-requests/{documentRequest}/reject',
    [DocumentRequestController::class, 'reject']
)->name('document-requests.reject');

Route::post(
    'document-requests/{documentRequest}/cancel',
    [DocumentRequestController::class, 'cancel']
)->name('document-requests.cancel');

/*
|--------------------------------------------------------------------------
| Individual Document Item Lifecycle
|--------------------------------------------------------------------------
*/

Route::post(
    'document-requests/{documentRequest}/items/{documentRequestItem}/start-processing',
    [DocumentRequestController::class, 'startItemProcessing']
)->name('document-requests.items.start-processing');

Route::post(
    'document-requests/{documentRequest}/items/{documentRequestItem}/ready-for-release',
    [DocumentRequestController::class, 'markItemReadyForRelease']
)->name('document-requests.items.ready-for-release');

Route::post(
    'document-requests/{documentRequest}/items/{documentRequestItem}/release',
    [DocumentRequestController::class, 'releaseItem']
)->name('document-requests.items.release');

Route::post(
    'document-requests/{documentRequest}/items/{documentRequestItem}/unavailable',
    [DocumentRequestController::class, 'markItemUnavailable']
)->name('document-requests.items.unavailable');

Route::post(
    'document-requests/{documentRequest}/items/{documentRequestItem}/cancel',
    [DocumentRequestController::class, 'cancelItem']
)->name('document-requests.items.cancel');