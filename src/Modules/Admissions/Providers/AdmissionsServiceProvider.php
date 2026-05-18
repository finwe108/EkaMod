<?php

namespace Modules\Admissions\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Registers the Admissions module.
 *
 * This module owns the public admission application flow and the admin
 * admission review workflow.
 *
 * Existing URLs, route names, middleware, and business behavior are preserved.
 *
 * Module: Admissions
 * Layer: Service Provider
 */
class AdmissionsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the Admissions module.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadViewsFrom(
            base_path('Modules/Admissions/resources/views'),
            'admissions'
        );

        $this->registerPublicRoutes();
        $this->registerAdminRoutes();
    }

    /**
     * Register public admission routes.
     *
     * @return void
     */
    protected function registerPublicRoutes(): void
    {
        Route::middleware(['web'])
            ->group(base_path('Modules/Admissions/routes/public.php'));
    }

    /**
     * Register admin admission review routes.
     *
     * @return void
     */
    protected function registerAdminRoutes(): void
    {
        Route::middleware(['web', 'auth', 'role:super_admin,admin,hr'])
            ->prefix('admin')
            ->name('admin.')
            ->group(base_path('Modules/Admissions/routes/admin.php'));
    }
}