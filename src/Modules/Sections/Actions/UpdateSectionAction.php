<?php

namespace Modules\Sections\Actions;

use App\Models\Section;

/**
 * Handles updating section records.
 *
 * Module: Sections
 * Layer: Action
 */
class UpdateSectionAction
{
    /**
     * Update an existing section.
     *
     * @param Section $section
     * @param array<string, mixed> $data
     * @return Section
     */
    public function execute(Section $section, array $data): Section
    {
        $section->update($data);

        return $section;
    }
}