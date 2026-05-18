<?php

namespace Modules\Employees\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Registers the Employees module.
 *
 * Module: Employees
 * Layer: Service Provider
 */
class EmployeesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(
            base_path('Modules/Employees/resources/views'),
            'employees'
        );

        $this->registerRoutes();
    }

    protected function registerRoutes(): void
    {
        Route::middleware(['web', 'auth', 'role:super_admin,admin,hr'])
            ->prefix('admin')
            ->name('admin.')
            ->group(base_path('Modules/Employees/routes/admin.php'));
    }
}