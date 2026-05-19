<?php

namespace Modules\Notifications\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Registers the Notifications module.
 *
 * This module owns authenticated user notification pages and read-status
 * actions while preserving the existing route names and URLs.
 *
 * Module: Notifications
 * Layer: Service Provider
 */
class NotificationsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the Notifications module.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadViewsFrom(
            base_path('Modules/Notifications/resources/views'),
            'notifications'
        );

        $this->registerRoutes();
    }

    /**
     * Register authenticated notification routes.
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        Route::middleware(['web', 'auth'])
            ->group(base_path('Modules/Notifications/routes/web.php'));
    }
}