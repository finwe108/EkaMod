<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create an exception-based school calendar.
     *
     * Normal calendar behavior:
     * - Monday-Friday are working days.
     * - Saturday-Sunday are non-working days.
     *
     * This table stores only exceptions to that default.
     *
     * Examples:
     * - A weekday holiday is stored with is_working_day = false.
     * - A special Saturday processing day is stored with is_working_day = true.
     */
    public function up(): void
    {
        if (Schema::hasTable('school_calendar_exceptions')) {
            return;
        }

        Schema::create('school_calendar_exceptions', function (Blueprint $table) {
            $table->id();

            $table->date('calendar_date')->unique();

            $table->boolean('is_working_day')->default(false);

            $table->string('name', 255);

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['calendar_date', 'is_working_day']);
        });
    }

    /**
     * Drop the school calendar exceptions table.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_calendar_exceptions');
    }
};