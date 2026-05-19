<?php

namespace Modules\SchoolYears\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Registers the School Years module.
 *
 * Module: SchoolYears
 * Layer: Service Provider
 */
class SchoolYearsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the School Years module.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadViewsFrom(
            base_path('Modules/SchoolYears/resources/views'),
            'school_years'
        );

        $this->registerRoutes();
    }

    /**
     * Register school year admin routes.
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        Route::middleware(['web', 'auth', 'role:super_admin,admin,hr'])
            ->prefix('admin')
            ->name('admin.')
            ->group(base_path('Modules/SchoolYears/routes/admin.php'));
    }
}