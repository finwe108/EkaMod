<?php

namespace Modules\Academics\Actions\Admin;

use App\Models\Subject;

/**
 * Handles creation of subject records.
 *
 * Module: Academics
 * Layer: Action
 */
class CreateSubjectAction
{
    /**
     * Create a subject.
     *
     * @param array<string, mixed> $data
     * @return Subject
     */
    public function execute(array $data): Subject
    {
        return Subject::create($data);
    }
}