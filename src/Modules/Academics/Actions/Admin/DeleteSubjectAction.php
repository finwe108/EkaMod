<?php

namespace Modules\Academics\Actions\Admin;

use App\Models\Subject;

/**
 * Handles deletion of subject records.
 *
 * Module: Academics
 * Layer: Action
 */
class DeleteSubjectAction
{
    /**
     * Delete a subject.
     *
     * @param Subject $subject
     * @return void
     */
    public function execute(Subject $subject): void
    {
        $subject->delete();
    }
}