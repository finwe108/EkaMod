<?php

namespace Modules\Dashboard\Services;

use App\Support\Modules\ModuleRegistry;
use Illuminate\Support\Facades\Route;

/**
 * Builds role-aware dashboard module links.
 *
 * Module: Dashboard
 * Layer: Service
 */
class DashboardNavigationService
{
    /**
     * Get dashboard links available to the given user.
     *
     * @param mixed $user
     * @return array<string, array<int, array<string, mixed>>>
     */
    public function linksFor(mixed $user): array
    {
        $user->loadMissing('roles');

        $userRoles = $user->roles->pluck('name')->toArray();

        return collect(array_merge(
            config('dashboard.modules', []),
            app(ModuleRegistry::class)->dashboardLinks()
        ))
            ->filter(function (array $module) use ($userRoles) {
                if (! Route::has($module['route'])) {
                    return false;
                }

                return collect($module['roles'] ?? [])
                    ->intersect($userRoles)
                    ->isNotEmpty();
            })
            ->groupBy('group')
            ->map(fn ($items) => $items->values()->all())
            ->toArray();
    }
}