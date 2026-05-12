<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_load_terms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_load_id')->constrained('teacher_loads')->cascadeOnDelete();
            $table->unsignedTinyInteger('term_no');
            $table->timestamps();

            $table->unique(['teacher_load_id', 'term_no']);
            $table->index('term_no');
        });

        DB::table('teacher_loads')
            ->select('id')
            ->chunkById(500, function ($teacherLoads) {
                $now = now();
                $rows = [];

                foreach ($teacherLoads as $teacherLoad) {
                    foreach ([1, 2, 3] as $termNo) {
                        $rows[] = [
                            'teacher_load_id' => $teacherLoad->id,
                            'term_no' => $termNo,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }

                if (!empty($rows)) {
                    DB::table('teacher_load_terms')->insertOrIgnore($rows);
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_load_terms');
    }
};
