<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Route Loader
|--------------------------------------------------------------------------
|
| This file intentionally stays small during the modular monolith migration.
| Existing URLs, route names, middleware, and controllers are preserved.
|
| Phase 1 only splits route definitions by area.
|
*/

require __DIR__ . '/public.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/teacher.php';
require __DIR__ . '/student.php';