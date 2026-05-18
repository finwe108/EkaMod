<?php

namespace Modules\DocumentRequirementRules\Actions;

use App\Models\DocumentRequirementRule;

/**
 * Handles deletion of document requirement rules.
 *
 * Module: DocumentRequirementRules
 * Layer: Action
 */
class DeleteDocumentRequirementRuleAction
{
    public function execute(DocumentRequirementRule $documentRequirementRule): void
    {
        $documentRequirementRule->delete();
    }
}