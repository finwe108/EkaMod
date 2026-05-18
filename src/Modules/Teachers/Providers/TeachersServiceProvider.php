<?php

namespace Modules\Teachers\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Registers the Teachers module.
 *
 * This module owns teacher-facing pages and admin-side teacher load
 * management.
 *
 * Existing URLs, route names, middleware, and permissions are preserved.
 *
 * Module: Teachers
 * Layer: Service Provider
 */
class TeachersServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the Teachers module.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadViewsFrom(
            base_path('Modules/Teachers/resources/views'),
            'teachers'
        );

        $this->registerTeacherRoutes();
        $this->registerAdminRoutes();
    }

    /**
     * Register teacher-facing portal routes.
     *
     * @return void
     */
    protected function registerTeacherRoutes(): void
    {
        Route::middleware(['web', 'auth', 'role:teacher,super_admin,admin,hr'])
            ->prefix('teacher')
            ->name('teacher.')
            ->group(base_path('Modules/Teachers/routes/teacher.php'));
    }

    /**
     * Register admin-side teacher management routes.
     *
     * @return void
     */
    protected function registerAdminRoutes(): void
    {
        Route::middleware(['web', 'auth', 'role:super_admin,admin,hr'])
            ->prefix('admin')
            ->name('admin.')
            ->group(base_path('Modules/Teachers/routes/admin.php'));
    }
}