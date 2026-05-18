<?php

namespace Modules\Students\Tests\Feature;

use Tests\TestCase;

/**
 * Tests admin student credential routes.
 *
 * Module: Students
 * Layer: Feature Test
 */
class AdminStudentCredentialRoutesTest extends TestCase
{
    /**
     * Ensure the credential create route name preserves URL generation.
     *
     * @return void
     */
    public function test_student_credentials_create_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/admin/students/1/credentials/create'),
            route('admin.students.credentials.store', 1)
        );
    }

    /**
     * Ensure the credential edit route name preserves URL generation.
     *
     * @return void
     */
    public function test_student_credentials_edit_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/admin/students/1/credentials'),
            route('admin.students.credentials.edit', 1)
        );
    }
}