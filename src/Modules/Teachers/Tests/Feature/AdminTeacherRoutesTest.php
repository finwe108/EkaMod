<?php

namespace Modules\Teachers\Tests\Feature;

use Tests\TestCase;

/**
 * Tests admin teacher profile routes.
 *
 * Module: Teachers
 * Layer: Feature Test
 */
class AdminTeacherRoutesTest extends TestCase
{
    public function test_admin_teachers_index_requires_authentication(): void
    {
        $response = $this->get(route('admin.teachers.index'));

        $response->assertRedirect(route('login', ['expired' => 1]));
    }

    public function test_admin_teachers_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/admin/teachers'),
            route('admin.teachers.index')
        );
    }
}