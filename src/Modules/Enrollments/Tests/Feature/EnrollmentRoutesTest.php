<?php

namespace Modules\Enrollments\Tests\Feature;

use Tests\TestCase;

/**
 * Tests enrollment module routes.
 *
 * Module: Enrollments
 * Layer: Feature Test
 */
class EnrollmentRoutesTest extends TestCase
{
    public function test_enrollments_index_requires_authentication(): void
    {
        $response = $this->get(route('admin.enrollments.index'));

        $response->assertRedirect(route('login', ['expired' => 1]));
    }

    public function test_enrollments_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/admin/enrollments'),
            route('admin.enrollments.index')
        );
    }

    public function test_ajax_sections_by_school_year_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/admin/ajax/sections-by-school-year'),
            route('admin.ajax.sections-by-school-year')
        );
    }
}