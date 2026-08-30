<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the students table for fresh installations and testing.
     *
     * Existing installations may already have this table because the
     * original database schema was created manually.
     */
    public function up(): void
    {
        if (Schema::hasTable('students')) {
            return;
        }

        Schema::create('students', function (Blueprint $table) {
            $table->id();

            $table->string('student_id', 50)->unique();

            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('suffix', 20)->nullable();

            $table->date('birth_date')->nullable();
            $table->string('birth_place', 150)->nullable();
            $table->string('mother_tongue', 100)->nullable();

            $table->boolean('is_ip')->default(false);
            $table->string('ethnic_group', 100)->nullable();
            $table->string('religion', 100)->nullable();

            $table->string('lrn', 50)->nullable()->unique();

            $table->string('email')->nullable();
            $table->string('contact_number', 30)->nullable();

            $table->text('address')->nullable();
            $table->string('house_street', 150)->nullable();
            $table->string('barangay', 100)->nullable();
            $table->string('municipality_city', 100)->nullable();
            $table->string('province', 100)->nullable();

            $table->string('father_name', 150)->nullable();
            $table->string('father_contact', 30)->nullable();

            $table->string('mother_name', 150)->nullable();
            $table->string('mother_contact', 30)->nullable();

            $table->string('guardian_name')->nullable();
            $table->string('guardian_relationship', 100)->nullable();
            $table->string('parent_guardian_contact', 30)->nullable();

            $table->string('remarks', 255)->nullable();
            $table->string('guardian_contact', 30)->nullable();

            $table->string('status', 20)->default('Active');

            $table->string('photo_path')->nullable();
            $table->string('sex', 20)->nullable();

            $table->timestamps();

            $table->index('last_name', 'idx_students_last_name');
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
