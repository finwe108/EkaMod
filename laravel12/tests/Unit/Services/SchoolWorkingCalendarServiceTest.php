<?php

namespace Tests\Unit\Services;

use App\Models\SchoolCalendarException;
use App\Models\SchoolSetting;
use App\Services\SchoolWorkingCalendarService;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SchoolWorkingCalendarServiceTest extends TestCase
{
    protected SchoolWorkingCalendarService $calendar;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createTestTables();

        $this->calendar = app(SchoolWorkingCalendarService::class);

        SchoolSetting::current();
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('school_calendar_exceptions');
        Schema::dropIfExists('school_settings');

        parent::tearDown();
    }

    protected function createTestTables(): void
    {
        Schema::dropIfExists('school_calendar_exceptions');
        Schema::dropIfExists('school_settings');

        Schema::create('school_settings', function (Blueprint $table) {
            $table->id();

            $table->string('school_id')->nullable();
            $table->string('region')->nullable();
            $table->string('division')->nullable();
            $table->string('district')->nullable();
            $table->string('school_name')->nullable();
            $table->string('school_head_name')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('short_name')->nullable();
            $table->string('tagline')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();

            $table->time('document_request_sla_cutoff_time')
                ->default('15:00:00');

            $table->timestamps();
        });

        Schema::create('school_calendar_exceptions', function (Blueprint $table) {
            $table->id();
            $table->date('calendar_date')->unique();
            $table->boolean('is_working_day')->default(false);
            $table->string('name');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['calendar_date', 'is_working_day']);
        });
    }

    public function test_monday_to_friday_are_working_days_by_default(): void
    {
        $this->assertTrue(
            $this->calendar->isWorkingDay('2026-09-07')
        );

        $this->assertTrue(
            $this->calendar->isWorkingDay('2026-09-08')
        );

        $this->assertTrue(
            $this->calendar->isWorkingDay('2026-09-09')
        );

        $this->assertTrue(
            $this->calendar->isWorkingDay('2026-09-10')
        );

        $this->assertTrue(
            $this->calendar->isWorkingDay('2026-09-11')
        );
    }

    public function test_saturday_and_sunday_are_non_working_by_default(): void
    {
        $this->assertFalse(
            $this->calendar->isWorkingDay('2026-09-05')
        );

        $this->assertFalse(
            $this->calendar->isWorkingDay('2026-09-06')
        );
    }

    public function test_weekday_holiday_can_be_marked_non_working(): void
    {
        SchoolCalendarException::create([
            'calendar_date' => '2026-09-08',
            'is_working_day' => false,
            'name' => 'School Holiday',
        ]);

        $this->assertFalse(
            $this->calendar->isWorkingDay('2026-09-08')
        );
    }

    public function test_weekend_can_be_marked_as_special_working_day(): void
    {
        SchoolCalendarException::create([
            'calendar_date' => '2026-09-05',
            'is_working_day' => true,
            'name' => 'Special Saturday Processing',
        ]);

        $this->assertTrue(
            $this->calendar->isWorkingDay('2026-09-05')
        );
    }

    public function test_next_working_day_skips_weekend(): void
    {
        $next = $this->calendar->nextWorkingDay('2026-09-05');

        $this->assertSame(
            '2026-09-07',
            $next->toDateString()
        );
    }

    public function test_next_working_day_skips_holiday(): void
    {
        SchoolCalendarException::create([
            'calendar_date' => '2026-09-08',
            'is_working_day' => false,
            'name' => 'School Holiday',
        ]);

        $next = $this->calendar->nextWorkingDay('2026-09-08');

        $this->assertSame(
            '2026-09-09',
            $next->toDateString()
        );
    }

    public function test_three_working_days_from_monday_is_wednesday(): void
    {
        $result = $this->calendar->addWorkingDays(
            '2026-09-07',
            3
        );

        $this->assertSame(
            '2026-09-09',
            $result->toDateString()
        );
    }

    public function test_three_working_days_crosses_weekend(): void
    {
        $result = $this->calendar->addWorkingDays(
            '2026-09-11',
            3
        );

        $this->assertSame(
            '2026-09-15',
            $result->toDateString()
        );
    }

    public function test_two_fifty_nine_pm_counts_current_working_day(): void
    {
        $start = $this->calendar->effectiveSlaStart(
            Carbon::parse('2026-09-07 14:59:00')
        );

        $this->assertSame(
            '2026-09-07 15:00:00',
            $start->format('Y-m-d H:i:s')
        );
    }

    public function test_three_pm_counts_current_working_day(): void
    {
        $start = $this->calendar->effectiveSlaStart(
            Carbon::parse('2026-09-07 15:00:00')
        );

        $this->assertSame(
            '2026-09-07 15:00:00',
            $start->format('Y-m-d H:i:s')
        );
    }

    public function test_after_three_pm_starts_next_working_day(): void
    {
        $start = $this->calendar->effectiveSlaStart(
            Carbon::parse('2026-09-07 15:01:00')
        );

        $this->assertSame(
            '2026-09-08 15:00:00',
            $start->format('Y-m-d H:i:s')
        );
    }

    public function test_friday_before_cutoff_with_three_day_sla_is_due_tuesday(): void
    {
        $due = $this->calendar->calculateDueAt(
            Carbon::parse('2026-09-11 14:00:00'),
            3
        );

        $this->assertSame(
            '2026-09-15 15:00:00',
            $due->format('Y-m-d H:i:s')
        );
    }

    public function test_friday_after_cutoff_with_three_day_sla_is_due_wednesday(): void
    {
        $due = $this->calendar->calculateDueAt(
            Carbon::parse('2026-09-11 16:00:00'),
            3
        );

        $this->assertSame(
            '2026-09-16 15:00:00',
            $due->format('Y-m-d H:i:s')
        );
    }

    public function test_holiday_is_excluded_from_sla_calculation(): void
    {
        SchoolCalendarException::create([
            'calendar_date' => '2026-09-08',
            'is_working_day' => false,
            'name' => 'School Holiday',
        ]);

        $due = $this->calendar->calculateDueAt(
            Carbon::parse('2026-09-07 14:00:00'),
            3
        );

        $this->assertSame(
            '2026-09-10 15:00:00',
            $due->format('Y-m-d H:i:s')
        );
    }

    public function test_cutoff_time_is_configurable(): void
    {
        $setting = SchoolSetting::current();

        $setting->update([
            'document_request_sla_cutoff_time' => '16:00:00',
        ]);

        $start = $this->calendar->effectiveSlaStart(
            Carbon::parse('2026-09-07 15:30:00')
        );

        $this->assertSame(
            '2026-09-07 16:00:00',
            $start->format('Y-m-d H:i:s')
        );
    }
}