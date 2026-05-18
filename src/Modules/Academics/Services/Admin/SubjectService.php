<?php

namespace Modules\Academics\Services\Admin;

use App\Models\Subject;
use Modules\Academics\Actions\Admin\CreateSubjectAction;
use Modules\Academics\Actions\Admin\DeleteSubjectAction;
use Modules\Academics\Actions\Admin\UpdateSubjectAction;

/**
 * Handles subject write workflows.
 *
 * Module: Academics
 * Layer: Service
 */
class SubjectService
{
    /**
     * Create a subject.
     *
     * @param array<string, mixed> $data
     * @return Subject
     */
    public function create(array $data): Subject
    {
        return app(CreateSubjectAction::class)->execute($data);
    }

    /**
     * Update a subject.
     *
     * @param Subject $subject
     * @param array<string, mixed> $data
     * @return Subject
     */
    public function update(Subject $subject, array $data): Subject
    {
        return app(UpdateSubjectAction::class)->execute($subject, $data);
    }

    /**
     * Delete a subject.
     *
     * @param Subject $subject
     * @return void
     */
    public function delete(Subject $subject): void
    {
        app(DeleteSubjectAction::class)->execute($subject);
    }
}