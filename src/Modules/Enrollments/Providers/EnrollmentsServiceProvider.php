<?php

namespace Modules\Enrollments\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Registers the Enrollments module.
 *
 * This module currently owns enrollment CRUD, status updates, and AJAX section
 * lookup routes only.
 *
 * SF1 report generation remains in the legacy controller until it is migrated
 * separately into a Reports or SF1 module.
 *
 * Module: Enrollments
 * Layer: Service Provider
 */
class EnrollmentsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the Enrollments module.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadViewsFrom(
            base_path('Modules/Enrollments/resources/views'),
            'enrollments'
        );

        $this->registerRoutes();
    }

    /**
     * Register enrollment admin routes.
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        Route::middleware(['web', 'auth', 'role:super_admin,admin,hr'])
            ->prefix('admin')
            ->name('admin.')
            ->group(base_path('Modules/Enrollments/routes/admin.php'));
    }
}