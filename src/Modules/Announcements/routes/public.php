<?php

use Illuminate\Support\Facades\Route;
use Modules\Announcements\Http\Controllers\Public\PublicAnnouncementController;

/*
|--------------------------------------------------------------------------
| Public Announcement Routes
|--------------------------------------------------------------------------
|
| Handles public-facing announcement/news pages.
|
*/

Route::get('/news', [PublicAnnouncementController::class, 'index'])
    ->name('public.news.index');

Route::get('/news/{announcement}', [PublicAnnouncementController::class, 'show'])
    ->name('public.news.show');