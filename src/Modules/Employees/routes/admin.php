<?php

use Illuminate\Support\Facades\Route;
use Modules\Employees\Http\Controllers\EmployeeController;

/*
|--------------------------------------------------------------------------
| Employee Management Routes
|--------------------------------------------------------------------------
|
| Preserves existing admin employee URLs, route names, middleware, and RBAC.
|
*/

Route::resource('employees', EmployeeController::class);