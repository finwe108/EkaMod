<?php

namespace Modules\Academics\Actions\Admin;

use App\Models\Subject;

/**
 * Handles updating subject records.
 *
 * Module: Academics
 * Layer: Action
 */
class UpdateSubjectAction
{
    /**
     * Update a subject.
     *
     * @param Subject $subject
     * @param array<string, mixed> $data
     * @return Subject
     */
    public function execute(Subject $subject, array $data): Subject
    {
        $subject->update($data);

        return $subject;
    }
}