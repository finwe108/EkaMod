<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'document_request_item_status_histories',
            function (Blueprint $table) {
                $table->id();

                /*
                 * The document request item whose status changed.
                 */
                $table->unsignedBigInteger(
                    'document_request_item_id'
                );

                /*
                 * Status transition.
                 */
                $table->string('from_status', 30);

                $table->string('to_status', 30);

                /*
                 * User who performed the transition.
                 */
                $table->unsignedBigInteger(
                    'changed_by_user_id'
                )->nullable();

                /*
                 * Optional reason or remarks.
                 */
                $table->text('remarks')
                    ->nullable();

                $table->timestamps();

                /*
                 * Explicit short foreign key names.
                 * This avoids MySQL's 64-character identifier limit.
                 */
                $table->foreign(
                    'document_request_item_id',
                    'drish_item_fk'
                )
                    ->references('id')
                    ->on('document_request_items')
                    ->cascadeOnDelete();

                $table->foreign(
                    'changed_by_user_id',
                    'drish_user_fk'
                )
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete();

                /*
                 * Explicit short index name.
                 */
                $table->index(
                    [
                        'document_request_item_id',
                        'created_at',
                    ],
                    'drish_item_created_idx'
                );
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'document_request_item_status_histories'
        );
    }
};