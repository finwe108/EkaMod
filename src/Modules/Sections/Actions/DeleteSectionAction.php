<?php

namespace Modules\Sections\Actions;

use App\Models\Section;

/**
 * Handles deletion of section records.
 *
 * Module: Sections
 * Layer: Action
 */
class DeleteSectionAction
{
    /**
     * Delete an existing section.
     *
     * @param Section $section
     * @return void
     */
    public function execute(Section $section): void
    {
        $section->delete();
    }
}