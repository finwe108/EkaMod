<?php

namespace Modules\DocumentTypes\Tests\Feature;

use Tests\TestCase;

/**
 * Tests document type module routes.
 *
 * Module: DocumentTypes
 * Layer: Feature Test
 */
class DocumentTypeRoutesTest extends TestCase
{
    /**
     * Ensure the document types index route exists and is protected.
     *
     * @return void
     */
    public function test_document_types_index_requires_authentication(): void
    {
        $response = $this->get(route('admin.document-types.index'));

        $response->assertRedirect(route('login', ['expired' => 1]));
    }

    public function test_document_types_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/admin/document-types'),
            route('admin.document-types.index')
        );
    }
}