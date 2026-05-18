<?php

namespace Modules\Teachers\Services\Admin;

use App\Models\Teacher;

/**
 * Handles teacher profile write workflows.
 *
 * Module: Teachers
 * Layer: Service
 */
class TeacherService
{
    /**
     * Create a teacher profile.
     *
     * @param array<string, mixed> $data
     * @return Teacher
     */
    public function create(array $data): Teacher
    {
        return Teacher::create($data);
    }

    /**
     * Update a teacher profile.
     *
     * @param Teacher $teacher
     * @param array<string, mixed> $data
     * @return Teacher
     */
    public function update(Teacher $teacher, array $data): Teacher
    {
        $teacher->update($data);

        return $teacher;
    }

    /**
     * Delete a teacher profile.
     *
     * @param Teacher $teacher
     * @return void
     */
    public function delete(Teacher $teacher): void
    {
        $teacher->delete();
    }
}