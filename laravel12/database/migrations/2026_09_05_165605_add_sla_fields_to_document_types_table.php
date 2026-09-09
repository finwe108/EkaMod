<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add SLA configuration fields to document types.
     *
     * Existing document types receive the default SLA of 3 working days.
     */
    public function up(): void
    {
        if (! Schema::hasTable('document_types')) {
            return;
        }

        Schema::table('document_types', function (Blueprint $table) {
            if (! Schema::hasColumn('document_types', 'sla_enabled')) {
                $table->boolean('sla_enabled')
                    ->default(true)
                    ->after('sort_order');
            }

            if (! Schema::hasColumn('document_types', 'sla_working_days')) {
                $table->unsignedInteger('sla_working_days')
                    ->default(3)
                    ->after('sla_enabled');
            }
        });
    }

    /**
     * Remove SLA configuration fields from document types.
     */
    public function down(): void
    {
        if (! Schema::hasTable('document_types')) {
            return;
        }

        Schema::table('document_types', function (Blueprint $table) {
            $columns = [];

            if (Schema::hasColumn('document_types', 'sla_working_days')) {
                $columns[] = 'sla_working_days';
            }

            if (Schema::hasColumn('document_types', 'sla_enabled')) {
                $columns[] = 'sla_enabled';
            }

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};