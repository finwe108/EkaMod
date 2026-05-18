<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds school identity and contact fields to the school_settings table.
 *
 * These fields allow the system to be customized for different schools
 * without hardcoding school branding/contact details in views.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds school short name, tagline, contact details, address, and logo path.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('school_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('school_settings', 'short_name')) {
                $table->string('short_name', 50)
                    ->nullable()
                    ->after('school_name');
            }

            if (! Schema::hasColumn('school_settings', 'tagline')) {
                $table->string('tagline', 255)
                    ->nullable()
                    ->after('short_name');
            }

            if (! Schema::hasColumn('school_settings', 'logo_path')) {
                $table->string('logo_path', 255)
                    ->nullable()
                    ->after('tagline');
            }

            if (! Schema::hasColumn('school_settings', 'phone')) {
                $table->string('phone', 50)
                    ->nullable()
                    ->after('school_head_name');
            }

            if (! Schema::hasColumn('school_settings', 'email')) {
                $table->string('email', 150)
                    ->nullable()
                    ->after('phone');
            }

            if (! Schema::hasColumn('school_settings', 'address')) {
                $table->string('address', 255)
                    ->nullable()
                    ->after('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * Removes school branding/contact fields added by this migration.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('school_settings', function (Blueprint $table) {
            if (Schema::hasColumn('school_settings', 'address')) {
                $table->dropColumn('address');
            }

            if (Schema::hasColumn('school_settings', 'email')) {
                $table->dropColumn('email');
            }

            if (Schema::hasColumn('school_settings', 'phone')) {
                $table->dropColumn('phone');
            }

            if (Schema::hasColumn('school_settings', 'logo_path')) {
                $table->dropColumn('logo_path');
            }

            if (Schema::hasColumn('school_settings', 'tagline')) {
                $table->dropColumn('tagline');
            }

            if (Schema::hasColumn('school_settings', 'short_name')) {
                $table->dropColumn('short_name');
            }
        });
    }
};