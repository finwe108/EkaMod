<?php

namespace Modules\Teachers\Tests\Feature;

use Tests\TestCase;

/**
 * Tests admin teacher load routes.
 *
 * Module: Teachers
 * Layer: Feature Test
 */
class AdminTeacherLoadRoutesTest extends TestCase
{
    /**
     * Ensure teacher load index requires authentication.
     *
     * @return void
     */
    public function test_admin_teacher_loads_index_requires_authentication(): void
    {
        $response = $this->get(route('admin.teacher_loads.index'));

        $response->assertRedirect(route('login', ['expired' => 1]));
    }

    /**
     * Ensure teacher load route name preserves expected URL.
     *
     * @return void
     */
    public function test_admin_teacher_loads_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/admin/teacher_loads'),
            route('admin.teacher_loads.index')
        );
    }
}