<?php

namespace Modules\Students\Tests\Feature;

use Tests\TestCase;

/**
 * Tests admin-side student document upload route.
 *
 * Module: Students
 * Layer: Feature Test
 */
class AdminStudentDocumentUploadRoutesTest extends TestCase
{
    public function test_admin_student_document_upload_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/admin/students/1/documents/2/upload'),
            route('admin.students.documents.upload', [1, 2])
        );
    }
}