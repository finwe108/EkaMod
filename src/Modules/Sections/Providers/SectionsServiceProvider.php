<?php

namespace Modules\Sections\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Registers the Sections module.
 *
 * This module manages administrative section records while preserving
 * existing admin URLs, route names, middleware, and permissions.
 *
 * Module: Sections
 * Layer: Service Provider
 */
class SectionsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the Sections module.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadViewsFrom(
            base_path('Modules/Sections/resources/views'),
            'sections'
        );

        $this->registerRoutes();
    }

    /**
     * Register section admin routes.
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        Route::middleware(['web', 'auth', 'role:super_admin,admin,hr'])
            ->prefix('admin')
            ->name('admin.')
            ->group(base_path('Modules/Sections/routes/admin.php'));
    }
}