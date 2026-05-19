<?php

use App\Support\Modules\ModuleRegistry;

$moduleProviders = [];

if (class_exists(ModuleRegistry::class)) {
    $moduleProviders = app(ModuleRegistry::class)->enabledProviders();
}

return array_merge([
    App\Providers\AppServiceProvider::class,
], $moduleProviders);