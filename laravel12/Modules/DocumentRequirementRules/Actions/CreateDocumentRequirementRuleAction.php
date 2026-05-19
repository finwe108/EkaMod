<?php

namespace Modules\DocumentRequirementRules\Actions;

use App\Models\DocumentRequirementRule;

/**
 * Handles creation of document requirement rules.
 *
 * Module: DocumentRequirementRules
 * Layer: Action
 */
class CreateDocumentRequirementRuleAction
{
    public function execute(array $data): DocumentRequirementRule
    {
        return DocumentRequirementRule::create($data);
    }
}