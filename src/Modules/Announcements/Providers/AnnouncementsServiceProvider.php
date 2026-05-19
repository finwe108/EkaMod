<?php

namespace Modules\Announcements\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Registers the Announcements module with the Laravel application.
 *
 * This provider loads both admin announcement management routes and
 * public announcement/news routes.
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

        $this->registerAdminRoutes();
        $this->registerPublicRoutes();
    }

    /**
     * Register admin announcement routes.
     *
     * @return void
     */
    protected function registerAdminRoutes(): void
    {
        Route::middleware(['web', 'auth', 'role:super_admin,admin,hr'])
            ->prefix('admin')
            ->name('admin.')
            ->group(base_path('Modules/Announcements/routes/admin.php'));
    }

    /**
     * Register public announcement/news routes.
     *
     * @return void
     */
    protected function registerPublicRoutes(): void
    {
        Route::middleware(['web'])
            ->group(base_path('Modules/Announcements/routes/public.php'));
    }
}