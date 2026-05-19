<?php

namespace Modules\SchoolYears\Tests\Feature;

use Tests\TestCase;

/**
 * Tests school year module routes.
 *
 * Module: SchoolYears
 * Layer: Feature Test
 */
class SchoolYearRoutesTest extends TestCase
{
    /**
     * Ensure school year index requires authentication.
     *
     * @return void
     */
    public function test_school_years_index_requires_authentication(): void
    {
        $response = $this->get(route('admin.school_years.index'));

        $response->assertRedirect(route('login', ['expired' => 1]));
    }

    /**
     * Ensure school year route name preserves the expected URL.
     *
     * @return void
     */
    public function test_school_years_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/admin/school_years'),
            route('admin.school_years.index')
        );
    }
}