<?php

namespace Modules\StudentDocuments\Tests\Feature;

use Tests\TestCase;

/**
 * Tests student document verification module routes.
 *
 * Module: StudentDocuments
 * Layer: Feature Test
 */
class StudentDocumentRoutesTest extends TestCase
{
    public function test_student_documents_index_requires_authentication(): void
    {
        $response = $this->get(route('admin.student-documents.index'));

        $response->assertRedirect(route('login', ['expired' => 1]));
    }

    public function test_student_documents_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/admin/student-documents'),
            route('admin.student-documents.index')
        );
    }
}