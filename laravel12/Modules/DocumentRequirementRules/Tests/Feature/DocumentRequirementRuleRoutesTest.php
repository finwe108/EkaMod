<?php

namespace Modules\DocumentRequirementRules\Tests\Feature;

use Tests\TestCase;

/**
 * Tests document requirement rule module routes.
 *
 * Module: DocumentRequirementRules
 * Layer: Feature Test
 */
class DocumentRequirementRuleRoutesTest extends TestCase
{
    /**
     * Ensure the document requirement rules index route exists and is protected.
     *
     * @return void
     */
    public function test_document_requirement_rules_index_requires_authentication(): void
    {
        $response = $this->get(route('admin.document-requirement-rules.index'));

        $response->assertRedirect(route('login', ['expired' => 1]));
    }

    /**
     * Ensure the existing route name still generates the expected URL.
     *
     * @return void
     */
    public function test_document_requirement_rules_route_name_generates_expected_url(): void
    {
        $this->assertEquals(
            url('/admin/document-requirement-rules'),
            route('admin.document-requirement-rules.index')
        );
    }
}