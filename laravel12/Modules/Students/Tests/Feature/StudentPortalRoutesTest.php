<?php

namespace Modules\Students\Tests\Feature;

use Tests\TestCase;

/**
 * Tests student portal routes.
 *
 * Module: Students
 * Layer: Feature Test
 */
class StudentPortalRoutesTest extends TestCase
{
    public function test_student_dashboard_requires_authentication(): void
    {
        $response = $this->get(route('student.dashboard'));

        $response->assertRedirect(route('login', ['expired' => 1]));
    }

    public function test_student_dashboard_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/student/dashboard'),
            route('student.dashboard')
        );
    }

    public function test_student_profile_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/student/profile'),
            route('student.profile.show')
        );
    }

    public function test_student_account_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/student/account'),
            route('student.account.edit')
        );
    }

    public function test_student_password_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/student/change-password'),
            route('student.password.edit')
        );
    }

    public function test_student_documents_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/student/documents'),
            route('student.documents.index')
        );
    }
}