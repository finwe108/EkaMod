<?php

namespace Modules\Sections\Actions;

use App\Models\Section;

/**
 * Handles creation of section records.
 *
 * Module: Sections
 * Layer: Action
 */
class CreateSectionAction
{
    /**
     * Create a new section.
     *
     * @param array<string, mixed> $data
     * @return Section
     */
    public function execute(array $data): Section
    {
        return Section::create($data);
    }
}