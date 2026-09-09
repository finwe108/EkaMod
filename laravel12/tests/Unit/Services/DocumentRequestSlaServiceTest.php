<?php

namespace Tests\Unit\Services;

use App\Models\DocumentRequestItem;
use App\Models\DocumentType;
use App\Models\SchoolCalendarException;
use App\Models\SchoolSetting;
use App\Services\DocumentRequestSlaService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DocumentRequestSlaServiceTest extends TestCase
{
    protected DocumentRequestSlaService $sla;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createTestTables();
        $this->sla = app(DocumentRequestSlaService::class);

        SchoolSetting::current();
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('school_calendar_exceptions');
        Schema::dropIfExists('school_settings');

        parent::tearDown();
    }

    public function test_disabled_document_type_does_not_have_an_sla(): void
    {
        $item = $this->itemWithDocumentType([
            'sla_enabled' => false,
        ]);

        $this->assertFalse($this->sla->isEnabled($item));
        $this->assertNull(
            $this->sla->calculate($item, '2026-09-07 09:00:00')
        );
    }

    public function test_unset_working_days_default_to_three(): void
    {
        $item = $this->itemWithDocumentType([
            'sla_enabled' => true,
        ]);

        $timestamps = $this->sla->calculate(
            $item,
            '2026-09-07 09:00:00'
        );

        $this->assertSame(
            '2026-09-07 15:00:00',
            $timestamps['sla_started_at']->format('Y-m-d H:i:s')
        );
        $this->assertSame(
            '2026-09-09 15:00:00',
            $timestamps['sla_due_at']->format('Y-m-d H:i:s')
        );
    }

    public function test_configured_working_days_and_cutoff_are_used(): void
    {
        SchoolSetting::current()->update([
            'document_request_sla_cutoff_time' => '16:00:00',
        ]);

        $item = $this->itemWithDocumentType([
            'sla_enabled' => true,
            'sla_working_days' => 2,
        ]);

        $timestamps = $this->sla->calculate(
            $item,
            '2026-09-07 15:30:00'
        );

        $this->assertSame(
            '2026-09-07 16:00:00',
            $timestamps['sla_started_at']->format('Y-m-d H:i:s')
        );
        $this->assertSame(
            '2026-09-08 16:00:00',
            $timestamps['sla_due_at']->format('Y-m-d H:i:s')
        );
    }

    public function test_after_cutoff_starts_on_the_next_working_day(): void
    {
        $item = $this->itemWithDocumentType([
            'sla_enabled' => true,
            'sla_working_days' => 1,
        ]);

        $timestamps = $this->sla->calculate(
            $item,
            '2026-09-07 15:01:00'
        );

        $this->assertSame(
            '2026-09-08 15:00:00',
            $timestamps['sla_started_at']->format('Y-m-d H:i:s')
        );
        $this->assertSame(
            '2026-09-08 15:00:00',
            $timestamps['sla_due_at']->format('Y-m-d H:i:s')
        );
    }

    public function test_calendar_exceptions_are_used_for_sla_calculation(): void
    {
        SchoolCalendarException::create([
            'calendar_date' => '2026-09-08',
            'is_working_day' => false,
            'name' => 'School Holiday',
        ]);

        $item = $this->itemWithDocumentType([
            'sla_enabled' => true,
            'sla_working_days' => 3,
        ]);

        $timestamps = $this->sla->calculate(
            $item,
            '2026-09-07 09:00:00'
        );

        $this->assertSame(
            '2026-09-10 15:00:00',
            $timestamps['sla_due_at']->format('Y-m-d H:i:s')
        );
    }

    protected function itemWithDocumentType(array $attributes): DocumentRequestItem
    {
        $item = new DocumentRequestItem();
        $item->setRelation('documentType', new DocumentType($attributes));

        return $item;
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
}
