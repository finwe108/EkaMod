<?php

use Illuminate\Support\Facades\Route;
use Modules\PublicSite\Http\Controllers\PublicPageController;

/*
|--------------------------------------------------------------------------
| Public Site Routes
|--------------------------------------------------------------------------
|
| Handles public-facing website pages.
|
| Existing URLs and route names are preserved.
|
*/

Route::get('/', [PublicPageController::class, 'home'])
    ->name('public.home');

Route::get('/about', [PublicPageController::class, 'about'])
    ->name('public.about');

Route::get('/privacy', [PublicPageController::class, 'privacy'])
    ->name('public.privacy');

Route::get('/admission', [PublicPageController::class, 'admission']);

Route::get('/tesda', [PublicPageController::class, 'tesda'])
    ->name('public.tesda');