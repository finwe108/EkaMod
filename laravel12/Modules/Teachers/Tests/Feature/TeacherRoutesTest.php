<?php

namespace Modules\Teachers\Tests\Feature;

use Tests\TestCase;

/**
 * Tests teacher module routes.
 *
 * Module: Teachers
 * Layer: Feature Test
 */
class TeacherRoutesTest extends TestCase
{
    /**
     * Ensure teacher classes route requires authentication.
     *
     * @return void
     */
    public function test_teacher_classes_requires_authentication(): void
    {
        $response = $this->get(route('teacher.classes'));

        $response->assertRedirect(route('login', ['expired' => 1]));
    }

    /**
     * Ensure teacher classes route name keeps the expected URL.
     *
     * @return void
     */
    public function test_teacher_classes_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/teacher/classes'),
            route('teacher.classes')
        );
    }

    /**
     * Ensure teacher grades route name keeps the expected URL.
     *
     * @return void
     */
    public function test_teacher_grades_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/teacher/grades'),
            route('teacher.grades')
        );
    }

    /**
     * Ensure teacher attendance route name keeps the expected URL.
     *
     * @return void
     */
    public function test_teacher_attendance_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/teacher/attendance'),
            route('teacher.attendance')
        );
    }

    /**
     * Ensure the preserved schedule route keeps its existing URL.
     *
     * @return void
     */
    public function test_teacher_schedule_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/teacher/schedule'),
            route('teacher.schedule')
        );
    }
}