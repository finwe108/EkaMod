<?php

namespace Modules\Auth\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Registers the Auth module.
 *
 * This module owns login and logout routes while preserving existing URLs,
 * route names, middleware, and authentication behavior.
 *
 * Module: Auth
 * Layer: Service Provider
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the Auth module.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadViewsFrom(
            base_path('Modules/Auth/resources/views'),
            'auth'
        );

        $this->registerRoutes();
    }

    /**
     * Register authentication routes.
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        Route::middleware(['web'])
            ->group(base_path('Modules/Auth/routes/web.php'));
    }
}