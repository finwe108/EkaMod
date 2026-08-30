<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('document_request_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('document_request_id')
                ->constrained('document_requests')
                ->cascadeOnDelete();

            $table->foreignId('document_type_id')
                ->constrained('document_types')
                ->restrictOnDelete();

            $table->string('status', 30)
                ->default('pending');

            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->index([
                'document_request_id',
                'status',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_request_items');
    }
};
