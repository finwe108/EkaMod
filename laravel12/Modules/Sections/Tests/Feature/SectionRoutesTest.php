<?php

namespace Modules\Sections\Tests\Feature;

use Tests\TestCase;

/**
 * Tests section module routes.
 *
 * Module: Sections
 * Layer: Feature Test
 */
class SectionRoutesTest extends TestCase
{
    /**
     * Ensure section index requires authentication.
     *
     * @return void
     */
    public function test_sections_index_requires_authentication(): void
    {
        $response = $this->get(route('admin.sections.index'));

        $response->assertRedirect(route('login', ['expired' => 1]));
    }

    /**
     * Ensure section route name preserves the expected URL.
     *
     * @return void
     */
    public function test_sections_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/admin/sections'),
            route('admin.sections.index')
        );
    }
}