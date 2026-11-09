<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add the configurable document-request SLA cutoff time.
     *
     * Default:
     * 15:00:00 (3:00 PM)
     */
    public function up(): void
    {
        if (! Schema::hasTable('school_settings')) {
            return;
        }

        if (! Schema::hasColumn('school_settings', 'document_request_sla_cutoff_time')) {
            Schema::table('school_settings', function (Blueprint $table) {
                $table->time('document_request_sla_cutoff_time')
                    ->default('15:00:00')
                    ->after('address');
            });
        }
    }

    /**
     * Remove the document-request SLA cutoff time.
     */
    public function down(): void
    {
        if (! Schema::hasTable('school_settings')) {
            return;
        }

        if (Schema::hasColumn('school_settings', 'document_request_sla_cutoff_time')) {
            Schema::table('school_settings', function (Blueprint $table) {
                $table->dropColumn('document_request_sla_cutoff_time');
            });
        }
    }
};