<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add SLA tracking timestamps to document request items.
     *
     * SLA is measured independently for each requested document.
     */
    public function up(): void
    {
        if (! Schema::hasTable('document_request_items')) {
            return;
        }

        Schema::table('document_request_items', function (Blueprint $table) {
            if (! Schema::hasColumn('document_request_items', 'sla_started_at')) {
                $table->timestamp('sla_started_at')
                    ->nullable()
                    ->after('status');
            }

            if (! Schema::hasColumn('document_request_items', 'sla_due_at')) {
                $table->timestamp('sla_due_at')
                    ->nullable()
                    ->after('sla_started_at');
            }

            if (! Schema::hasColumn('document_request_items', 'sla_completed_at')) {
                $table->timestamp('sla_completed_at')
                    ->nullable()
                    ->after('sla_due_at');
            }
        });
    }

    /**
     * Remove SLA tracking timestamps.
     */
    public function down(): void
    {
        if (! Schema::hasTable('document_request_items')) {
            return;
        }

        Schema::table('document_request_items', function (Blueprint $table) {
            $columns = [];

            if (Schema::hasColumn('document_request_items', 'sla_completed_at')) {
                $columns[] = 'sla_completed_at';
            }

            if (Schema::hasColumn('document_request_items', 'sla_due_at')) {
                $columns[] = 'sla_due_at';
            }

            if (Schema::hasColumn('document_request_items', 'sla_started_at')) {
                $columns[] = 'sla_started_at';
            }

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};