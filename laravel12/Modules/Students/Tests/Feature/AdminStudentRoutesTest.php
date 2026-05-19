<?php

namespace Modules\Students\Tests\Feature;

use Tests\TestCase;

/**
 * Tests admin student routes.
 *
 * Module: Students
 * Layer: Feature Test
 */
class AdminStudentRoutesTest extends TestCase
{
    public function test_admin_students_index_requires_authentication(): void
    {
        $response = $this->get(route('admin.students.index'));

        $response->assertRedirect(route('login', ['expired' => 1]));
    }

    public function test_admin_students_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/admin/students'),
            route('admin.students.index')
        );
    }

    public function test_admin_students_sections_route_name_generates_expected_legacy_url(): void
    {
        $this->assertEquals(
            url('/admin/admin/students/sections/1'),
            route('admin.students.sections', 1)
        );
    }
}