<?php

namespace App\Support\Modules;

/**
 * Discovers and reads local module manifests.
 *
 * This registry intentionally avoids Laravel facades because it may be used
 * very early during application bootstrapping while providers are being
 * discovered.
 */
class ModuleRegistry
{
    /**
     * Return all discovered enabled modules.
     *
     * @return array<int, array<string, mixed>>
     */
    public function enabledModules(): array
    {
        return array_values(array_filter(
            $this->allModules(),
            fn (array $module) => ($module['enabled'] ?? false) === true
        ));
    }

    /**
     * Return all discovered module manifests.
     *
     * @return array<int, array<string, mixed>>
     */
    public function allModules(): array
    {
        $modulesPath = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'Modules';

        if (! is_dir($modulesPath)) {
            return [];
        }

        $modules = [];

        $directories = scandir($modulesPath);

        if ($directories === false) {
            return [];
        }

        foreach ($directories as $directory) {
            if ($directory === '.' || $directory === '..') {
                continue;
            }

            $modulePath = $modulesPath . DIRECTORY_SEPARATOR . $directory;

            if (! is_dir($modulePath)) {
                continue;
            }

            $manifestPath = $modulePath . DIRECTORY_SEPARATOR . 'module.php';

            if (! file_exists($manifestPath)) {
                continue;
            }

            $manifest = require $manifestPath;

            if (! is_array($manifest)) {
                continue;
            }

            $manifest['path'] = $modulePath;

            $modules[] = $manifest;
        }

        return $modules;
    }

    /**
     * Return service providers from enabled modules.
     *
     * @return array<int, class-string>
     */
    public function enabledProviders(): array
    {
        $providers = [];

        foreach ($this->enabledModules() as $module) {
            if (! empty($module['provider'])) {
                $providers[] = $module['provider'];
            }
        }

        return $providers;
    }

    /**
     * Return navigation links from enabled modules.
     *
     * @return array<int, array<string, mixed>>
     */
    public function navigationLinks(): array
    {
        $links = [];

        foreach ($this->enabledModules() as $module) {
            foreach (($module['navigation'] ?? []) as $item) {
                $links[] = $item;
            }
        }

        return $links;
    }

    /**
     * Return dashboard links from enabled modules.
     *
     * @return array<int, array<string, mixed>>
     */
    public function dashboardLinks(): array
    {
        $links = [];

        foreach ($this->enabledModules() as $module) {
            foreach (($module['dashboard'] ?? []) as $item) {
                $links[] = $item;
            }
        }

        return $links;
    }
}