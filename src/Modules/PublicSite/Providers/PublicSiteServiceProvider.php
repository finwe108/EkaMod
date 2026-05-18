<?php

namespace Modules\PublicSite\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Registers the Public Site module.
 *
 * This module owns static/public-facing website pages such as home,
 * about, privacy, TESDA, and admission landing redirect.
 *
 * Module: PublicSite
 * Layer: Service Provider
 */
class PublicSiteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the Public Site module.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadViewsFrom(
            base_path('Modules/PublicSite/resources/views'),
            'public_site'
        );

        $this->registerRoutes();
    }

    /**
     * Register public site routes.
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        Route::middleware(['web'])
            ->group(base_path('Modules/PublicSite/routes/web.php'));
    }
}