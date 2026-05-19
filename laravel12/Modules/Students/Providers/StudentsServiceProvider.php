<?php

namespace Modules\Students\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Registers the Students module.
 *
 * This module owns the student-facing portal routes, views, controllers,
 * requests, services, and actions.
 *
 * Existing URLs, route names, authentication behavior, and student workflows
 * are preserved during consolidation.
 *
 * Module: Students
 * Layer: Service Provider
 */
class StudentsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the Students module.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadViewsFrom(
            base_path('Modules/Students/resources/views'),
            'students'
        );

        $this->registerRoutes();
        $this->registerAdminRoutes();
    }

    /**
     * Register student-facing portal routes.
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        Route::middleware(['web', 'auth'])
            ->prefix('student')
            ->name('student.')
            ->group(base_path('Modules/Students/routes/student.php'));
    }

    /**
     * Register admin-side student management routes.
     *
     * @return void
     */
    protected function registerAdminRoutes(): void
    {
        Route::middleware(['web', 'auth', 'role:super_admin,admin,hr'])
            ->prefix('admin')
            ->name('admin.')
            ->group(base_path('Modules/Students/routes/admin.php'));
    }
}