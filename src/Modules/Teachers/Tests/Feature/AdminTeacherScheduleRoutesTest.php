<?php

namespace Modules\Teachers\Tests\Feature;

use Tests\TestCase;

/**
 * Tests admin teacher schedule routes.
 *
 * Module: Teachers
 * Layer: Feature Test
 */
class AdminTeacherScheduleRoutesTest extends TestCase
{
    /**
     * Ensure teacher schedule route name preserves expected URL.
     *
     * @return void
     */
    public function test_admin_teacher_schedule_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/admin/teachers/1/schedule'),
            route('admin.teachers.schedule', 1)
        );
    }
}