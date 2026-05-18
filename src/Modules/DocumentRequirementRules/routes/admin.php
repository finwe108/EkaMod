<?php

use Illuminate\Support\Facades\Route;
use Modules\DocumentRequirementRules\Http\Controllers\DocumentRequirementRuleController;

/*
|--------------------------------------------------------------------------
| Document Requirement Rule Routes
|--------------------------------------------------------------------------
|
| Handles administrative CRUD operations for document requirement rules.
| These routes preserve the existing admin URL prefix and route names.
|
*/

Route::resource('document-requirement-rules', DocumentRequirementRuleController::class)
    ->except(['show']);