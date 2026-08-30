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
        Schema::create('document_requests', function (Blueprint $table) {
            $table->id();

            $table->string('request_number', 30)->unique();

            $table->foreignId('student_id')
                ->constrained('students')
                ->restrictOnDelete();

            $table->foreignId('requested_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('requested_by_name');
            $table->string('requested_by_contact', 100)->nullable();
            $table->string('requester_relationship', 50)->nullable();

            $table->string('status', 30)
                ->default('pending');

            $table->string('purpose')->nullable();

            $table->timestamp('requested_at');

            $table->timestamp('processed_at')->nullable();

            $table->timestamp('released_at')->nullable();

            $table->foreignId('processed_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('released_to_name')->nullable();

            $table->text('release_notes')->nullable();

            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->index(['student_id', 'status']);
            $table->index(['status', 'requested_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_requests');
    }
};
