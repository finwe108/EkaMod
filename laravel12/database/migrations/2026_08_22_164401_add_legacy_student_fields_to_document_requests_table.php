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
        Schema::table('document_requests', function (Blueprint $table) {
            /*
             * Existing system students continue using student_id.
             *
             * Pre-system / legacy students may not have a record in
             * the current students table, so this relationship must
             * be optional.
             */
            $table->string('student_record_type', 20)
                ->default('existing')
                ->after('student_id');

            $table->string('legacy_first_name', 100)
                ->nullable()
                ->after('student_record_type');

            $table->string('legacy_middle_name', 100)
                ->nullable()
                ->after('legacy_first_name');

            $table->string('legacy_last_name', 100)
                ->nullable()
                ->after('legacy_middle_name');

            $table->date('legacy_birth_date')
                ->nullable()
                ->after('legacy_last_name');
        });

        /*
         * Make the current student relationship optional.
         *
         * Existing requests retain their student_id values.
         * Legacy requests will use NULL for student_id and store
         * the student's historical identity fields instead.
         */
        Schema::table('document_requests', function (Blueprint $table) {
            $table->foreignId('student_id')
                ->nullable()
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_requests', function (Blueprint $table) {
            $table->dropColumn([
                'student_record_type',
                'legacy_first_name',
                'legacy_middle_name',
                'legacy_last_name',
                'legacy_birth_date',
            ]);
        });

        Schema::table('document_requests', function (Blueprint $table) {
            $table->foreignId('student_id')
                ->nullable(false)
                ->change();
        });
    }
};