<?php

use Illuminate\Support\Facades\Route;
use Modules\Announcements\Http\Controllers\AnnouncementController;

/*
|--------------------------------------------------------------------------
| Announcement Management Routes
|--------------------------------------------------------------------------
|
| Handles administrative CRUD operations for school announcements.
|
| Important migration rule:
| The URL, route name, middleware, and behavior must remain the same as the
| original monolith route:
|
| URL prefix: /admin/announcements
| Route name: admin.announcements.*
|
*/

Route::resource('announcements', AnnouncementController::class);