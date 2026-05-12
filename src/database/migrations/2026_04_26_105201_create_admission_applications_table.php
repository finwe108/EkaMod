<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admission_applications', function (Blueprint $table) {
            $table->id();

            $table->string('application_number')->unique();

            $table->foreignId('school_year_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('grade_level_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('section_id')->nullable()->constrained()->nullOnDelete();

            $table->enum('student_type', ['new', 'transferee', 'returning'])->default('new');

            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->string('sex');
            $table->date('birth_date');

            $table->string('birth_place')->nullable();
            $table->string('mother_tongue')->nullable();
            $table->boolean('is_ip')->default(false);
            $table->string('ethnic_group')->nullable();
            $table->string('religion')->nullable();
            $table->string('lrn')->nullable();

            $table->string('email')->nullable();
            $table->string('contact_number')->nullable();

            $table->string('address')->nullable();
            $table->string('house_street')->nullable();
            $table->string('barangay')->nullable();
            $table->string('municipality_city')->nullable();
            $table->string('province')->nullable();

            $table->string('father_name')->nullable();
            $table->string('father_contact')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_contact')->nullable();

            $table->string('guardian_name')->nullable();
            $table->string('guardian_relationship')->nullable();
            $table->string('guardian_contact')->nullable();
            $table->string('parent_guardian_contact')->nullable();

            $table->string('last_school_attended')->nullable();
            $table->string('last_grade_level_completed')->nullable();
            $table->string('strand_or_track')->nullable();

            $table->enum('application_status', [
                'submitted',
                'under_review',
                'accepted',
                'rejected',
                'cancelled',
            ])->default('submitted');

            $table->text('remarks')->nullable();
            $table->text('rejection_reason')->nullable();

            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();

            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('accepted_student_id')->nullable()->constrained('students')->nullOnDelete();
            $table->foreignId('created_user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index(['application_status', 'created_at'], 'admission_status_created_idx');
            $table->index(['school_year_id', 'grade_level_id'], 'admission_sy_grade_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admission_applications');
    }
};