<?php

use Illuminate\Support\Facades\Route;
use Modules\Reports\Http\Controllers\SF1ReportController;

/*
|--------------------------------------------------------------------------
| Report Routes
|--------------------------------------------------------------------------
|
| Handles school reports. SF1 is the first migrated report area.
|
| Existing URLs and route names are preserved.
|
*/

Route::get('reports/sf1', [SF1ReportController::class, 'filter'])
    ->name('reports.sf1.filter');

Route::get('reports/sf1/print', [SF1ReportController::class, 'print'])
    ->name('reports.sf1.print');

Route::post('reports/sf1/queue', [SF1ReportController::class, 'queue'])
    ->name('reports.sf1.queue');

Route::get('reports/sf1/generated', [SF1ReportController::class, 'generated'])
    ->name('reports.sf1.generated');

Route::post('reports/sf1/generated/{report}/generate', [SF1ReportController::class, 'generate'])
    ->name('reports.sf1.generated.generate');

Route::get('reports/sf1/generated/{report}/download', [SF1ReportController::class, 'downloadGenerated'])
    ->name('reports.sf1.generated.download');