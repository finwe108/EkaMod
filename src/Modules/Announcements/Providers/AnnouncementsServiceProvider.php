<?php

namespace Modules\Announcements\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Registers the Announcements module with the Laravel application.
 *
 * This provider currently loads only the module routes while preserving
 * the existing admin URL prefix, route names, middleware, and permissions.
 *
 * Module: Announcements
 * Layer: Service Provider
 */
class AnnouncementsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the Announcements module.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->mergeConfigFrom(
            base_path('Modules/Announcements/config/config.php'),
            'announcements'
        );
        
        $this->loadViewsFrom(
            base_path('Modules/Announcements/resources/views'),
            'announcements'
        );

        $this->registerRoutes();
    }

    /**
     * Register Announcements module routes.
     *
     * Routes are wrapped with the same admin group used by the original
     * monolith routes to preserve backward compatibility.
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        Route::middleware(['web', 'auth', 'role:super_admin,admin,hr'])
            ->prefix('admin')
            ->name('admin.')
            ->group(base_path('Modules/Announcements/routes/admin.php'));
    }
}