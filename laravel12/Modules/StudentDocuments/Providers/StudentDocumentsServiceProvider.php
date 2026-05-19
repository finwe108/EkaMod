<?php

namespace Modules\StudentDocuments\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Registers the Student Documents module.
 *
 * Module: StudentDocuments
 * Layer: Service Provider
 */
class StudentDocumentsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the module.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadViewsFrom(
            base_path('Modules/StudentDocuments/resources/views'),
            'student_documents'
        );

        $this->registerRoutes();
    }

    /**
     * Register module routes while preserving existing admin URLs,
     * route names, middleware, and RBAC behavior.
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        Route::middleware(['web', 'auth', 'role:super_admin,admin,hr'])
            ->prefix('admin')
            ->name('admin.')
            ->group(base_path('Modules/StudentDocuments/routes/admin.php'));
    }
}