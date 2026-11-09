<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add the student foreign key after the students table exists.
     */
    public function up(): void
    {
        if (! Schema::hasTable('document_requests') || ! Schema::hasTable('students')) {
            throw new RuntimeException(
                'Cannot add the document request student foreign key: document_requests or students table is missing.'
            );
        }

        foreach (Schema::getForeignKeys('document_requests') as $foreignKey) {
            if (in_array('student_id', $foreignKey['columns'] ?? [], true)) {
                if (
                    ($foreignKey['foreign_table'] ?? null) === 'students'
                    && ($foreignKey['foreign_columns'] ?? []) === ['id']
                ) {
                    // An existing installation already has the correct foreign key.
                    return;
                }

                throw new RuntimeException(
                    'A foreign key already exists on document_requests.student_id, but it does not reference students.id.'
                );
            }
        }

        Schema::table('document_requests', function (Blueprint $table) {
            $table->foreign('student_id', 'dr_student_fk')
                ->references('id')
                ->on('students')
                ->restrictOnDelete();
        });
    }

    /**
     * Remove only the foreign key created by this migration.
     *
     * Existing installations may have the original constraint name,
     * so that constraint must not be removed during rollback.
     */
    public function down(): void
    {
        if (! Schema::hasTable('document_requests')) {
            return;
        }

        foreach (Schema::getForeignKeys('document_requests') as $foreignKey) {
            if (($foreignKey['name'] ?? null) === 'dr_student_fk') {
                Schema::table('document_requests', function (Blueprint $table) {
                    $table->dropForeign('dr_student_fk');
                });

                break;
            }
        }
    }
};
