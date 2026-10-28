<?php

namespace App\Console\Commands;

use App\Models\DocumentRequestItem;
use Illuminate\Console\Command;
use Modules\DocumentRequests\Services\DocumentRequestSlaService;

class RecalculateDocumentRequestSla extends Command
{
    protected $signature = 'document-requests:sla-recalculate
                            {--dry-run : Show changes without saving them}
                            {--request= : Recalculate items belonging to a specific request ID}';

    protected $description = 'Recalculate due dates for existing active document request SLAs';

    public function handle(
        DocumentRequestSlaService $slaService
    ): int {
        $dryRun = (bool) $this->option('dry-run');
        $requestId = $this->option('request');

        $query = DocumentRequestItem::query()
            ->with('documentType')
            ->whereHas('documentType', function ($query) {
                $query->where('sla_enabled', true);
            })
            ->whereNotNull('sla_started_at')
            ->whereNull('sla_completed_at')
            ->whereNotIn('status', [
                'unavailable',
                'cancelled',
            ]);

        if ($requestId !== null) {
            $query->where(
                'document_request_id',
                $requestId
            );
        }

        $processed = 0;
        $changed = 0;
        $skippedMissingDueDate = 0;

        $this->info(
            $dryRun
                ? 'DRY RUN: no database changes will be made.'
                : 'Recalculating active document request SLAs...'
        );

        $query->chunkById(
            100,
            function ($items) use (
                $slaService,
                $dryRun,
                &$processed,
                &$changed,
                &$skippedMissingDueDate
            ) {
                foreach ($items as $item) {
                    $processed++;

                    if ($item->sla_due_at === null) {
                        $skippedMissingDueDate++;

                        $this->warn(
                            "Item #{$item->id}: missing SLA due date; manual review required."
                        );

                        continue;
                    }

                    $oldDueAt = $item->sla_due_at;

                    $newDueAt = $slaService->recalculateDueAt(
                        $item
                    );

                    if ($newDueAt === null) {
                        continue;
                    }

                    if (! $oldDueAt->equalTo($newDueAt)) {
                        $changed++;

                        $this->line(
                            sprintf(
                                'Item #%d: %s → %s',
                                $item->id,
                                $oldDueAt->format('Y-m-d H:i:s'),
                                $newDueAt->format('Y-m-d H:i:s')
                            )
                        );

                        if (! $dryRun) {
                            $item->forceFill([
                                'sla_due_at' => $newDueAt,
                            ])->saveQuietly();
                        }
                    }
                }
            }
        );

        $this->newLine();

        $this->info("Active SLA items checked: {$processed}");
        $this->info("SLA due dates changed: {$changed}");
        $this->info(
            "SLA items skipped for manual review (missing due date): {$skippedMissingDueDate}"
        );

        if ($dryRun) {
            $this->comment(
                'DRY RUN complete. No changes should have been retained.'
            );
        } else {
            $this->info(
                'SLA recalculation complete.'
            );
        }

        return self::SUCCESS;
    }
}
