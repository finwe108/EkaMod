<?php

use Illuminate\Support\Facades\Route;
use Modules\DocumentTypes\Http\Controllers\DocumentTypeController;

/*
|--------------------------------------------------------------------------
| Document Type Management Routes
|--------------------------------------------------------------------------
|
| Handles administrative CRUD operations for document types.
|
| Important migration rule:
| These routes are loaded inside the existing admin route group, so they
| preserve the original URL prefix, route names, middleware, and permissions.
|
| Existing URLs:
| /admin/document-types
|
| Existing route names:
| admin.document-types.*
|
*/

Route::resource('document-types', DocumentTypeController::class);