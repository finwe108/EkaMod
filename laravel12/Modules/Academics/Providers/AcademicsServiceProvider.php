<?php

namespace Modules\Academics\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Registers the Academics module.
 *
 * This module owns academic configuration areas such as subjects,
 * grade levels, grading profiles, terms, and curriculum-related setup.
 *
 * Module: Academics
 * Layer: Service Provider
 */
class AcademicsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the Academics module.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadViewsFrom(
            base_path('Modules/Academics/resources/views'),
            'academics'
        );

        $this->registerAdminRoutes();
    }

    /**
     * Register admin academic routes.
     *
     * @return void
     */
    protected function registerAdminRoutes(): void
    {
        Route::middleware(['web', 'auth', 'role:super_admin,admin,hr'])
            ->prefix('admin')
            ->name('admin.')
            ->group(base_path('Modules/Academics/routes/admin.php'));
    }
}