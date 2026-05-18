<?php

namespace Modules\SchoolSettings\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Registers the School Settings module with the Laravel application.
 *
 * This provider loads school setting routes using the same admin routing
 * structure as the original monolith implementation.
 *
 * Module: SchoolSettings
 * Layer: Service Provider
 */
class SchoolSettingsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the School Settings module.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadViewsFrom(
            base_path('Modules/SchoolSettings/resources/views'),
            'school_settings'
        );

        $this->registerRoutes();
    }

    /**
     * Register School Settings module routes.
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        Route::middleware(['web', 'auth', 'role:super_admin,admin,hr'])
            ->prefix('admin')
            ->name('admin.')
            ->group(base_path('Modules/SchoolSettings/routes/admin.php'));
    }
}