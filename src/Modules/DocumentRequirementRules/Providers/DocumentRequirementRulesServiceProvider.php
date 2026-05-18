<?php

namespace Modules\DocumentRequirementRules\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Registers the Document Requirement Rules module.
 *
 * Module: DocumentRequirementRules
 * Layer: Service Provider
 */
class DocumentRequirementRulesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the module.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadViewsFrom(
            base_path('Modules/DocumentRequirementRules/resources/views'),
            'document_requirement_rules'
        );

        $this->registerRoutes();
    }

    /**
     * Register module routes while preserving admin URL prefix,
     * route names, middleware, and RBAC behavior.
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        Route::middleware(['web', 'auth', 'role:super_admin,admin,hr'])
            ->prefix('admin')
            ->name('admin.')
            ->group(base_path('Modules/DocumentRequirementRules/routes/admin.php'));
    }
}