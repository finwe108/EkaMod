<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add lifecycle and release tracking fields to individual
     * document request items.
     */
    public function up(): void
    {
        Schema::table('document_request_items', function (Blueprint $table) {
            /*
             * Processing information.
             *
             * Tracks when actual preparation of this specific
             * document began and which user started processing it.
             */
            $table->timestamp('processing_started_at')
                ->nullable()
                ->after('status');

            $table->foreignId('processed_by_user_id')
                ->nullable()
                ->after('processing_started_at')
                ->constrained('users')
                ->nullOnDelete();

            /*
             * Ready-for-release information.
             *
             * This is separate from processing_started_at because a
             * document may take time to prepare.
             */
            $table->timestamp('ready_at')
                ->nullable()
                ->after('processed_by_user_id');

            /*
             * Individual release information.
             *
             * Each document may be released at a different time,
             * including partial releases from the same request.
             */
            $table->timestamp('released_at')
                ->nullable()
                ->after('ready_at');

            $table->string('released_to_name')
                ->nullable()
                ->after('released_at');

            $table->text('release_notes')
                ->nullable()
                ->after('released_to_name');

            /*
             * Unavailable documents must have an explanation.
             *
             * Example:
             * "No copy could be located in the available archives."
             */
            $table->text('unavailable_reason')
                ->nullable()
                ->after('release_notes');

            /*
             * Helps query items currently being worked on or ready
             * for release without scanning the entire table.
             */
            $table->index([
                'status',
                'document_request_id',
            ]);
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::table('document_request_items', function (Blueprint $table) {
            $table->dropIndex([
                'status',
                'document_request_id',
            ]);

            $table->dropForeign([
                'processed_by_user_id',
            ]);

            $table->dropColumn([
                'processing_started_at',
                'processed_by_user_id',
                'ready_at',
                'released_at',
                'released_to_name',
                'release_notes',
                'unavailable_reason',
            ]);
        });
    }
};
