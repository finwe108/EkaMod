<?php

use Illuminate\Support\Facades\Route;
use Modules\Notifications\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Notification Routes
|--------------------------------------------------------------------------
|
| Handles authenticated user notifications.
|
| Existing URLs and route names are preserved:
| /notifications
| notifications.*
|
*/

Route::get('/notifications', [NotificationController::class, 'index'])
    ->name('notifications.index');

Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
    ->name('notifications.readAll');

Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
    ->name('notifications.read');