<?php

namespace Modules\DocumentTypes\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Registers the Document Types module with the Laravel application.
 *
 * This provider loads document type routes while preserving existing
 * admin URLs, names, middleware, permissions, and controller behavior.
 *
 * Module: DocumentTypes
 * Layer: Service Provider
 */
class DocumentTypesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the Document Types module.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadViewsFrom(
            base_path('Modules/DocumentTypes/resources/views'),
            'document_types'
        );

        $this->registerRoutes();
    }

    /**
     * Register Document Types module routes.
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        Route::middleware(['web', 'auth', 'role:super_admin,admin,hr'])
            ->prefix('admin')
            ->name('admin.')
            ->group(base_path('Modules/DocumentTypes/routes/admin.php'));
    }
}