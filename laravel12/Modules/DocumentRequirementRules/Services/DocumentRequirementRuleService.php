<?php

namespace Modules\DocumentRequirementRules\Services;

use App\Models\DocumentRequirementRule;
use Modules\DocumentRequirementRules\Actions\CreateDocumentRequirementRuleAction;
use Modules\DocumentRequirementRules\Actions\DeleteDocumentRequirementRuleAction;
use Modules\DocumentRequirementRules\Actions\UpdateDocumentRequirementRuleAction;

/**
 * Handles document requirement rule persistence operations.
 *
 * Module: DocumentRequirementRules
 * Layer: Service
 */
class DocumentRequirementRuleService
{
    public function create(array $data): DocumentRequirementRule
    {
        return app(CreateDocumentRequirementRuleAction::class)->execute($data);
    }

    public function update(
        DocumentRequirementRule $documentRequirementRule,
        array $data
    ): DocumentRequirementRule {
        return app(UpdateDocumentRequirementRuleAction::class)
            ->execute($documentRequirementRule, $data);
    }

    public function delete(DocumentRequirementRule $documentRequirementRule): void
    {
        app(DeleteDocumentRequirementRuleAction::class)
            ->execute($documentRequirementRule);
    }
}