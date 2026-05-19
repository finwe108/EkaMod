<?php

namespace Modules\Dashboard\Services;

use App\Models\DocumentRequirementRule;
use App\Support\Modules\ModuleRegistry;
use Illuminate\Support\Facades\Route;

/**
 * Builds role-aware sidebar navigation links.
 *
 * This service centralizes sidebar link visibility so future modules can be
 * added to configuration instead of hardcoding links directly in Blade.
 *
 * Module: Dashboard
 * Layer: Service
 */
class SidebarNavigationService
{
    /**
     * Controls the display order of sidebar groups.
     *
     * Module manifests provide the links, but this service decides the final
     * sidebar section order.
     *
     * @var array<int, string>
     */
    protected array $groupOrder = [
        'Overview',
        'Administration',
        'Registrar',
        'Academics',
        'Teachers',
        'Documents',
        'Reports',
        'Teaching',
        'Student',
        'Account',
    ];
    /**
     * Build sidebar sections available to the authenticated user.
     *
     * @param mixed $user
     * @return array<int, array<string, mixed>>
     */
    public function sectionsFor(mixed $user): array
    {
        if (! $user) {
            return [];
        }

        $user->loadMissing(['roles', 'student']);

        $userRoles = $user->roles->pluck('name')->toArray();

        return collect($this->navigationSections())
            ->filter(function (array $section) use ($userRoles) {
                return $this->rolesMatch($section['roles'] ?? [], $userRoles);
            })
            ->map(function (array $section) use ($user, $userRoles) {
                $items = collect($section['items'] ?? [])
                    ->filter(function (array $item) use ($userRoles) {
                        return Route::has($item['route'])
                            && $this->rolesMatch($item['roles'] ?? [], $userRoles);
                    })
                    ->map(function (array $item) use ($user) {
                        $item['url'] = route($item['route']);
                        $item['is_active'] = request()->routeIs($item['active'] ?? $item['route']);
                        $item['badge_count'] = $this->resolveBadgeCount($item, $user);

                        return $item;
                    })
                    ->values()
                    ->all();

                return [
                    'label' => $section['label'],
                    'items' => $items,
                ];
            })
            ->filter(fn (array $section) => ! empty($section['items']))
            ->values()
            ->all();
    }


    /**
     * Get module-provided navigation sections.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function navigationSections(): array
    {
        $moduleLinks = app(\App\Support\Modules\ModuleRegistry::class)
            ->navigationLinks();

        return collect($moduleLinks)
            ->unique(function ($item) {
                return ($item['group'] ?? '') . '|' . ($item['route'] ?? '') . '|' . ($item['label'] ?? '');
            })
            ->groupBy('group')
            ->map(function ($items, $group) {
                return [
                    'label' => $group,
                    'roles' => collect($items)
                        ->flatMap(fn ($item) => $item['roles'] ?? [])
                        ->unique()
                        ->values()
                        ->all(),
                    'items' => $items
                        ->sortBy(fn ($item) => $item['order'] ?? 999)
                        ->values()
                        ->all(),
                ];
            })
            ->sortBy(function ($section) {
                $index = array_search($section['label'], $this->groupOrder, true);

                return $index === false ? 999 : $index;
            })
            ->values()
            ->all();
    }

    /**
     * Check whether the user has at least one of the allowed roles.
     *
     * @param array<int, string> $allowedRoles
     * @param array<int, string> $userRoles
     * @return bool
     */
    protected function rolesMatch(array $allowedRoles, array $userRoles): bool
    {
        return collect($allowedRoles)
            ->intersect($userRoles)
            ->isNotEmpty();
    }

    /**
     * Resolve optional sidebar badge counts.
     *
     * @param array<string, mixed> $item
     * @param mixed $user
     * @return int
     */
    protected function resolveBadgeCount(array $item, mixed $user): int
    {
        return match ($item['badge'] ?? null) {
            'required_documents' => $this->requiredDocumentsCount($user),
            default => 0,
        };
    }

    /**
     * Count required student documents that are not yet verified.
     *
     * This preserves the existing sidebar badge behavior while moving the
     * database query out of the Blade partial.
     *
     * @param mixed $user
     * @return int
     */
    protected function requiredDocumentsCount(mixed $user): int
    {
        if (! $user->roles->contains('name', 'student') || ! $user->student) {
            return 0;
        }

        $student = $user->student;

        $currentEnrollment = $student->currentEnrollment()->first()
            ?: $student->latestEnrollment()->first();

        $gradeLevelId = $currentEnrollment?->grade_level_id;
        $studentType = $currentEnrollment?->student_type;

        $requiredDocumentTypeIds = DocumentRequirementRule::query()
            ->where('is_required', true)
            ->whereHas('documentType', function ($query) {
                $query->where('is_active', true);
            })
            ->where(function ($query) use ($gradeLevelId) {
                $query->whereNull('grade_level_id')
                    ->orWhere('grade_level_id', $gradeLevelId);
            })
            ->where(function ($query) use ($studentType) {
                $query->whereNull('student_type')
                    ->orWhere('student_type', $studentType);
            })
            ->pluck('document_type_id')
            ->unique();

        $verifiedDocumentTypeIds = $student->documents()
            ->whereIn('document_type_id', $requiredDocumentTypeIds)
            ->where('is_verified', true)
            ->pluck('document_type_id');

        return $requiredDocumentTypeIds
            ->diff($verifiedDocumentTypeIds)
            ->count();
    }
}