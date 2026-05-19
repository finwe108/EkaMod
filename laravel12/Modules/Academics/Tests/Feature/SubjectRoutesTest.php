<?php

namespace Modules\Academics\Tests\Feature;

use Tests\TestCase;

/**
 * Tests subject routes.
 *
 * Module: Academics
 * Layer: Feature Test
 */
class SubjectRoutesTest extends TestCase
{
    /**
     * Ensure subject index requires authentication.
     *
     * @return void
     */
    public function test_subjects_index_requires_authentication(): void
    {
        $response = $this->get(route('admin.subjects.index'));

        $response->assertRedirect(route('login', ['expired' => 1]));
    }

    /**
     * Ensure subject route name preserves expected URL.
     *
     * @return void
     */
    public function test_subjects_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/admin/subjects'),
            route('admin.subjects.index')
        );
    }
}