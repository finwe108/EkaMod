<?php

namespace Modules\Dashboard\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Registers the Dashboard module.
 *
 * This module owns the role-aware dashboard entry point and admin dashboard
 * compatibility route.
 *
 * Module: Dashboard
 * Layer: Service Provider
 */
class DashboardServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the Dashboard module.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadViewsFrom(
            base_path('Modules/Dashboard/resources/views'),
            'dashboard'
        );

        $this->registerRoutes();
    }

    /**
     * Register dashboard routes.
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        Route::middleware(['web'])
            ->group(base_path('Modules/Dashboard/routes/web.php'));
    }
}