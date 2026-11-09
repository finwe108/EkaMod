<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Repair fresh installations or databases missing this table.
        // Never replace an existing school_settings table.
        if (Schema::hasTable('school_settings')) {
            return;
        }

        Schema::create('school_settings', function (Blueprint $table) {
            $table->id();

            $table->string('school_id', 50)->nullable();
            $table->string('region', 150)->nullable();
            $table->string('division', 150)->nullable();
            $table->string('district', 150)->nullable();
            $table->string('school_name')->nullable();
            $table->string('school_head_name')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        /*
         * Intentionally do not drop school_settings.
         *
         * This repair migration may run against a database where the
         * table already exists. Dropping it during rollback could destroy
         * existing school configuration and data.
         */
    }
};
