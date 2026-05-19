<?php

namespace Modules\SchoolSettings\Tests\Feature;

use Tests\TestCase;

/**
 * Tests school settings module routes.
 *
 * Module: SchoolSettings
 * Layer: Feature Test
 */
class SchoolSettingsRoutesTest extends TestCase
{
    /**
     * Ensure the school settings edit route exists and is protected.
     *
     * @return void
     */
    public function test_school_settings_edit_requires_authentication(): void
    {
        $response = $this->get(route('admin.school-settings.edit'));

        $response->assertRedirect(route('login', ['expired' => 1]));
    }

    public function test_school_settings_edit_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/admin/school-settings'),
            route('admin.school-settings.edit')
        );
    }
}