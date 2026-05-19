<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds publishing metadata and optional image support to announcements.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            if (! Schema::hasColumn('announcements', 'category')) {
                $table->string('category', 50)
                    ->nullable()
                    ->after('content');
            }

            if (! Schema::hasColumn('announcements', 'status')) {
                $table->string('status', 50)
                    ->default('published')
                    ->after('category');
            }

            if (! Schema::hasColumn('announcements', 'posted_at')) {
                $table->timestamp('posted_at')
                    ->nullable()
                    ->after('status');
            }

            if (! Schema::hasColumn('announcements', 'image_path')) {
                $table->string('image_path')
                    ->nullable()
                    ->after('posted_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            if (Schema::hasColumn('announcements', 'image_path')) {
                $table->dropColumn('image_path');
            }

            if (Schema::hasColumn('announcements', 'posted_at')) {
                $table->dropColumn('posted_at');
            }

            if (Schema::hasColumn('announcements', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('announcements', 'category')) {
                $table->dropColumn('category');
            }
        });
    }
};