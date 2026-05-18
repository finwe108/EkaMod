<?php

namespace Modules\DocumentRequirementRules\Actions;

use App\Models\DocumentRequirementRule;

/**
 * Handles updating document requirement rules.
 *
 * Module: DocumentRequirementRules
 * Layer: Action
 */
class UpdateDocumentRequirementRuleAction
{
    public function execute(
        DocumentRequirementRule $documentRequirementRule,
        array $data
    ): DocumentRequirementRule {
        $documentRequirementRule->update($data);

        return $documentRequirementRule;
    }
}