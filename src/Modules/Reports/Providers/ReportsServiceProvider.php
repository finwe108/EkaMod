<?php

namespace Modules\Reports\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Registers the Reports module.
 *
 * This module owns school reporting features such as SF1, SF2, SF9,
 * and future DepEd/CHED reporting workflows.
 *
 * Existing report URLs, route names, middleware, and permissions are preserved.
 *
 * Module: Reports
 * Layer: Service Provider
 */
class ReportsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the Reports module.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadViewsFrom(
            base_path('Modules/Reports/resources/views'),
            'reports'
        );

        $this->registerRoutes();
    }

    /**
     * Register admin report routes.
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        Route::middleware(['web', 'auth', 'role:super_admin,admin,hr'])
            ->prefix('admin')
            ->name('admin.')
            ->group(base_path('Modules/Reports/routes/admin.php'));
    }
}