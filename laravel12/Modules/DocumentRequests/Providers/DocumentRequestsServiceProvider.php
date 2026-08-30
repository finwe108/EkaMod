<?php

namespace Modules\DocumentRequests\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Registers the Document Requests module.
 *
 * Module: DocumentRequests
 * Layer: Service Provider
 */
class DocumentRequestsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the Document Requests module.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadViewsFrom(
            base_path('Modules/DocumentRequests/resources/views'),
            'document_requests'
        );

        $this->registerRoutes();
    }

    /**
     * Register Document Request admin routes.
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        Route::middleware(['web', 'auth', 'role:registrar,super_admin,admin'])
            ->prefix('admin')
            ->name('admin.')
            ->group(base_path('Modules/DocumentRequests/routes/admin.php'));
    }
}
