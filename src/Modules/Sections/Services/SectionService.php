<?php

namespace Modules\Sections\Services;

use App\Models\Section;
use Modules\Sections\Actions\CreateSectionAction;
use Modules\Sections\Actions\DeleteSectionAction;
use Modules\Sections\Actions\UpdateSectionAction;

/**
 * Handles section write workflows.
 *
 * This service preserves the existing behavior for creating, updating,
 * and deleting sections.
 *
 * Module: Sections
 * Layer: Service
 */
class SectionService
{
    /**
     * Create a section.
     *
     * @param array<string, mixed> $data
     * @return Section
     */
    public function create(array $data): Section
    {
        return app(CreateSectionAction::class)->execute($data);
    }

    /**
     * Update a section.
     *
     * @param Section $section
     * @param array<string, mixed> $data
     * @return Section
     */
    public function update(Section $section, array $data): Section
    {
        return app(UpdateSectionAction::class)->execute($section, $data);
    }

    /**
     * Delete a section.
     *
     * @param Section $section
     * @return void
     */
    public function delete(Section $section): void
    {
        app(DeleteSectionAction::class)->execute($section);
    }
}