<?php

namespace Modules\Admissions\Tests\Feature;

use Tests\TestCase;

/**
 * Tests admission module routes.
 *
 * Module: Admissions
 * Layer: Feature Test
 */
class AdmissionRoutesTest extends TestCase
{
    /**
     * Ensure public admission route preserves expected URL.
     *
     * @return void
     */
    public function test_public_admission_create_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/admission/apply'),
            route('public.admission.create')
        );
    }

    /**
     * Ensure admin admission applications require authentication.
     *
     * @return void
     */
    public function test_admin_admission_applications_requires_authentication(): void
    {
        $response = $this->get(route('admin.admission_applications.index'));

        $response->assertRedirect(route('login', ['expired' => 1]));
    }

    /**
     * Ensure admin admission application route preserves expected URL.
     *
     * @return void
     */
    public function test_admin_admission_applications_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/admin/admission-applications'),
            route('admin.admission_applications.index')
        );
    }
}