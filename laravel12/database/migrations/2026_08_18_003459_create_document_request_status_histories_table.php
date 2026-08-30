<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_request_status_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('document_request_id')
                ->constrained('document_requests')
                ->cascadeOnDelete();

            $table->string('from_status', 30)->nullable();

            $table->string('to_status', 30);

            $table->foreignId('changed_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->index(
                ['document_request_id', 'created_at'],
                'drsh_request_created_idx'
            );

            $table->index(
                ['to_status'],
                'drsh_status_idx'
            );

            $table->index([
                'to_status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_request_status_histories');
    }
};