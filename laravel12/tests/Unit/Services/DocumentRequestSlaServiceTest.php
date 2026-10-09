<?php

namespace Tests\Unit\Services;

use App\Models\DocumentRequest;
use App\Models\DocumentRequestItem;
use App\Enums\DocumentRequestItemStatus;
use App\Models\DocumentType;
use App\Models\SchoolCalendarException;
use App\Models\SchoolSetting;
use Modules\DocumentRequests\Services\DocumentRequestSlaService;
use Carbon\Carbon;
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
        Carbon::setTestNow();
        
        Schema::dropIfExists('document_request_items');
        Schema::dropIfExists('document_requests');
        Schema::dropIfExists('document_types');
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

        $this->assertNull(
            $this->sla->status($item, Carbon::parse('2026-09-07 09:00:00'))
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
            '2026-09-10 15:00:00',
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
            '2026-09-09 16:00:00',
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
            '2026-09-09 15:00:00',
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
            '2026-09-11 15:00:00',
            $timestamps['sla_due_at']->format('Y-m-d H:i:s')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SLA Deadline Recalculation Regression Tests
    |--------------------------------------------------------------------------
    */

    public function test_recalculation_excludes_the_sla_start_day(): void
    {
        $item = $this->itemWithDocumentType([
            'sla_enabled' => true,
            'sla_working_days' => 3,
        ], [
            'sla_started_at' => '2026-10-08 15:00:00',
            'sla_due_at' => '2026-10-12 15:00:00',
            'status' => DocumentRequestItemStatus::PROCESSING,
        ]);

        $dueAt = $this->sla->recalculateDueAt($item);

        $this->assertNotNull($dueAt);

        $this->assertSame(
            '2026-10-13 15:00:00',
            $dueAt->format('Y-m-d H:i:s')
        );

        $this->assertSame(
            '2026-10-08 15:00:00',
            $item->sla_started_at->format('Y-m-d H:i:s')
        );
    }

    public function test_recalculation_returns_the_same_deadline_when_already_correct(): void
    {
        $item = $this->itemWithDocumentType([
            'sla_enabled' => true,
            'sla_working_days' => 3,
        ], [
            'sla_started_at' => '2026-10-08 15:00:00',
            'sla_due_at' => '2026-10-13 15:00:00',
            'status' => DocumentRequestItemStatus::PROCESSING,
        ]);

        $dueAt = $this->sla->recalculateDueAt($item);

        $this->assertNotNull($dueAt);

        $this->assertSame(
            '2026-10-13 15:00:00',
            $dueAt->format('Y-m-d H:i:s')
        );
    }

    public function test_recalculation_skips_cancelled_items(): void
    {
        $item = $this->itemWithDocumentType([
            'sla_enabled' => true,
            'sla_working_days' => 3,
        ], [
            'status' => DocumentRequestItemStatus::CANCELLED,
            'sla_started_at' => '2026-10-08 15:00:00',
            'sla_due_at' => '2026-10-12 15:00:00',
        ]);

        $this->assertNull(
            $this->sla->recalculateDueAt($item)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SLA State
    |--------------------------------------------------------------------------
    */

    public function test_missing_sla_timestamps_are_not_started(): void
    {
        $item = $this->itemWithDocumentType([
            'sla_enabled' => true,
        ]);

        $this->assertSame(
            DocumentRequestSlaService::STATUS_NOT_STARTED,
            $this->sla->status(
                $item,
                Carbon::parse('2026-09-07 09:00:00')
            )
        );
    }

    public function test_active_sla_with_more_than_one_working_day_remaining_is_on_time(): void
    {
        $item = $this->itemWithDocumentType([
            'sla_enabled' => true,
        ], [
            'sla_started_at' => '2026-09-07 15:00:00',
            'sla_due_at' => '2026-09-11 15:00:00',
        ]);

        $this->assertSame(
            DocumentRequestSlaService::STATUS_ON_TIME,
            $this->sla->status(
                $item,
                Carbon::parse('2026-09-07 16:00:00')
            )
        );
    }

    public function test_active_sla_with_one_working_day_remaining_is_due_soon(): void
    {
        $item = $this->itemWithDocumentType([
            'sla_enabled' => true,
        ], [
            'sla_started_at' => '2026-09-07 15:00:00',
            'sla_due_at' => '2026-09-09 15:00:00',
        ]);

        $this->assertSame(
            DocumentRequestSlaService::STATUS_DUE_SOON,
            $this->sla->status(
                $item,
                Carbon::parse('2026-09-08 10:00:00')
            )
        );
    }

    public function test_friday_with_monday_deadline_is_due_soon(): void
    {
        $item = $this->itemWithDocumentType([
            'sla_enabled' => true,
        ], [
            'sla_started_at' => '2026-09-10 15:00:00',
            'sla_due_at' => '2026-09-14 15:00:00',
        ]);

        $this->assertSame(
            DocumentRequestSlaService::STATUS_DUE_SOON,
            $this->sla->status(
                $item,
                Carbon::parse('2026-09-11 10:00:00')
            )
        );
    }

    public function test_holiday_is_respected_when_determining_due_soon(): void
    {
        SchoolCalendarException::create([
            'calendar_date' => '2026-09-14',
            'is_working_day' => false,
            'name' => 'School Holiday',
        ]);

        $item = $this->itemWithDocumentType([
            'sla_enabled' => true,
        ], [
            'sla_started_at' => '2026-09-10 15:00:00',
            'sla_due_at' => '2026-09-15 15:00:00',
        ]);

        $this->assertSame(
            DocumentRequestSlaService::STATUS_DUE_SOON,
            $this->sla->status(
                $item,
                Carbon::parse('2026-09-11 10:00:00')
            )
        );
    }

    public function test_exact_deadline_is_still_due_soon(): void
    {
        $item = $this->itemWithDocumentType([
            'sla_enabled' => true,
        ], [
            'sla_started_at' => '2026-09-07 15:00:00',
            'sla_due_at' => '2026-09-09 15:00:00',
        ]);

        $this->assertSame(
            DocumentRequestSlaService::STATUS_DUE_SOON,
            $this->sla->status(
                $item,
                Carbon::parse('2026-09-09 15:00:00')
            )
        );
    }

    public function test_active_sla_after_deadline_is_overdue(): void
    {
        $item = $this->itemWithDocumentType([
            'sla_enabled' => true,
        ], [
            'sla_started_at' => '2026-09-07 15:00:00',
            'sla_due_at' => '2026-09-09 15:00:00',
        ]);

        $this->assertSame(
            DocumentRequestSlaService::STATUS_OVERDUE,
            $this->sla->status(
                $item,
                Carbon::parse('2026-09-09 15:00:01')
            )
        );

        $this->assertTrue(
            $this->sla->isOverdue(
                $item,
                Carbon::parse('2026-09-09 15:00:01')
            )
        );
    }

    public function test_release_at_deadline_is_completed_on_time(): void
    {
        $item = $this->itemWithDocumentType([
            'sla_enabled' => true,
        ], [
            'sla_started_at' => '2026-09-07 15:00:00',
            'sla_due_at' => '2026-09-09 15:00:00',
            'sla_completed_at' => '2026-09-09 15:00:00',
        ]);

        $this->assertSame(
            DocumentRequestSlaService::STATUS_COMPLETED_ON_TIME,
            $this->sla->status(
                $item,
                Carbon::parse('2026-09-10 09:00:00')
            )
        );
    }

    public function test_release_before_deadline_is_completed_on_time(): void
    {
        $item = $this->itemWithDocumentType([
            'sla_enabled' => true,
        ], [
            'sla_started_at' => '2026-09-07 15:00:00',
            'sla_due_at' => '2026-09-09 15:00:00',
            'sla_completed_at' => '2026-09-09 14:59:59',
        ]);

        $this->assertSame(
            DocumentRequestSlaService::STATUS_COMPLETED_ON_TIME,
            $this->sla->status(
                $item,
                Carbon::parse('2026-09-10 09:00:00')
            )
        );
    }

    public function test_release_after_deadline_is_completed_late(): void
    {
        $item = $this->itemWithDocumentType([
            'sla_enabled' => true,
        ], [
            'sla_started_at' => '2026-09-07 15:00:00',
            'sla_due_at' => '2026-09-09 15:00:00',
            'sla_completed_at' => '2026-09-09 15:00:01',
        ]);

        $this->assertSame(
            DocumentRequestSlaService::STATUS_COMPLETED_LATE,
            $this->sla->status(
                $item,
                Carbon::parse('2026-09-10 09:00:00')
            )
        );
    }

    public function test_completed_item_is_not_reported_as_overdue(): void
    {
        $item = $this->itemWithDocumentType([
            'sla_enabled' => true,
        ], [
            'sla_started_at' => '2026-09-07 15:00:00',
            'sla_due_at' => '2026-09-09 15:00:00',
            'sla_completed_at' => '2026-09-09 15:30:00',
        ]);

        $this->assertFalse(
            $this->sla->isOverdue(
                $item,
                Carbon::parse('2026-09-10 09:00:00')
            )
        );
    }

    public function test_cancelled_item_is_not_an_active_sla(): void
    {
        $item = $this->itemWithDocumentType([
            'sla_enabled' => true,
        ], [
            'status' => DocumentRequestItemStatus::CANCELLED,
            'sla_started_at' => '2026-09-07 15:00:00',
            'sla_due_at' => '2026-09-09 15:00:00',
        ]);

        $now = Carbon::parse('2026-09-10 09:00:00');

        $this->assertFalse(
            $this->sla->hasActiveSla($item)
        );

        $this->assertSame(
            DocumentRequestSlaService::STATUS_NOT_STARTED,
            $this->sla->status($item, $now)
        );

        $this->assertFalse(
            $this->sla->isOverdue($item, $now)
        );

        $this->assertFalse(
            $this->sla->isDueSoon($item, $now)
        );

        $this->assertNull(
            $this->sla->remaining($item, $now)
        );
    }

    public function test_unavailable_item_is_not_an_active_sla(): void
    {
        $item = $this->itemWithDocumentType([
            'sla_enabled' => true,
        ], [
            'status' => DocumentRequestItemStatus::UNAVAILABLE,
            'sla_started_at' => '2026-09-07 15:00:00',
            'sla_due_at' => '2026-09-09 15:00:00',
        ]);

        $now = Carbon::parse('2026-09-10 09:00:00');

        $this->assertFalse(
            $this->sla->hasActiveSla($item)
        );

        $this->assertSame(
            DocumentRequestSlaService::STATUS_NOT_STARTED,
            $this->sla->status($item, $now)
        );

        $this->assertFalse(
            $this->sla->isOverdue($item, $now)
        );

        $this->assertFalse(
            $this->sla->isDueSoon($item, $now)
        );

        $this->assertNull(
            $this->sla->remaining($item, $now)
        );
    }

    public function test_remaining_returns_seconds_until_deadline(): void
    {
        $item = $this->itemWithDocumentType([
            'sla_enabled' => true,
        ], [
            'sla_started_at' => '2026-09-07 15:00:00',
            'sla_due_at' => '2026-09-09 15:00:00',
        ]);

        $remaining = $this->sla->remaining(
            $item,
            Carbon::parse('2026-09-09 12:00:00')
        );

        $this->assertSame(10800, $remaining);
    }

    public function test_remaining_is_negative_after_deadline(): void
    {
        $item = $this->itemWithDocumentType([
            'sla_enabled' => true,
        ], [
            'sla_started_at' => '2026-09-07 15:00:00',
            'sla_due_at' => '2026-09-09 15:00:00',
        ]);

        $remaining = $this->sla->remaining(
            $item,
            Carbon::parse('2026-09-09 16:00:00')
        );

        $this->assertSame(-3600, $remaining);
    }

    public function test_remaining_is_null_for_completed_items(): void
    {
        $item = $this->itemWithDocumentType([
            'sla_enabled' => true,
        ], [
            'sla_started_at' => '2026-09-07 15:00:00',
            'sla_due_at' => '2026-09-09 15:00:00',
            'sla_completed_at' => '2026-09-09 14:00:00',
        ]);

        $this->assertNull(
            $this->sla->remaining(
                $item,
                Carbon::parse('2026-09-10 09:00:00')
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SLA Query Filters
    |--------------------------------------------------------------------------
    */

    public function test_overdue_filter_returns_request_with_overdue_active_item(): void
    {
        Carbon::setTestNow(
            Carbon::parse('2026-09-10 10:00:00')
        );

        $request = DocumentRequest::create([
            'request_number' => 'DR-OVERDUE-0001',
            'status' => 'processing',
            'requested_at' => '2026-09-07 09:00:00',
        ]);

        $documentType = DocumentType::create([
            'name' => 'Form 137',
            'sla_enabled' => true,
            'sla_working_days' => 3,
        ]);

        DocumentRequestItem::create([
            'document_request_id' => $request->id,
            'document_type_id' => $documentType->id,
            'status' => DocumentRequestItemStatus::PROCESSING,
            'sla_started_at' => '2026-09-07 15:00:00',
            'sla_due_at' => '2026-09-09 15:00:00',
        ]);

        $query = DocumentRequest::query();

        $this->sla->applyRequestFilter(
            $query,
            DocumentRequestSlaService::FILTER_OVERDUE
        );

        $this->assertTrue(
            $query->whereKey($request->id)->exists()
        );
    }

    public function test_due_soon_filter_returns_request_with_due_soon_item(): void
    {
        Carbon::setTestNow(
            Carbon::parse('2026-09-08 10:00:00')
        );

        $request = DocumentRequest::create([
            'request_number' => 'DR-DUE-SOON-0001',
            'status' => 'processing',
            'requested_at' => '2026-09-07 09:00:00',
        ]);

        $documentType = DocumentType::create([
            'name' => 'Form 137',
            'sla_enabled' => true,
            'sla_working_days' => 3,
        ]);

        DocumentRequestItem::create([
            'document_request_id' => $request->id,
            'document_type_id' => $documentType->id,
            'status' => DocumentRequestItemStatus::PROCESSING,
            'sla_started_at' => '2026-09-07 15:00:00',
            'sla_due_at' => '2026-09-09 15:00:00',
        ]);

        $query = DocumentRequest::query();

        $this->sla->applyRequestFilter(
            $query,
            DocumentRequestSlaService::FILTER_DUE_SOON
        );

        $this->assertTrue(
            $query->whereKey($request->id)->exists()
        );
    }

    public function test_on_time_filter_returns_request_with_more_than_one_working_day_remaining(): void
    {
        Carbon::setTestNow(
            Carbon::parse('2026-09-08 10:00:00')
        );

        $request = DocumentRequest::create([
            'request_number' => 'DR-ON-TIME-0001',
            'status' => 'processing',
            'requested_at' => '2026-09-07 09:00:00',
        ]);

        $documentType = DocumentType::create([
            'name' => 'Form 137',
            'sla_enabled' => true,
            'sla_working_days' => 3,
        ]);

        DocumentRequestItem::create([
            'document_request_id' => $request->id,
            'document_type_id' => $documentType->id,
            'status' => DocumentRequestItemStatus::PROCESSING,
            'sla_started_at' => '2026-09-07 15:00:00',
            'sla_due_at' => '2026-09-11 15:00:00',
        ]);

        $query = DocumentRequest::query();

        $this->sla->applyRequestFilter(
            $query,
            DocumentRequestSlaService::FILTER_ON_TIME
        );

        $this->assertTrue(
            $query->whereKey($request->id)->exists()
        );
    }

    public function test_sla_filter_excludes_disabled_document_type(): void
    {
        Carbon::setTestNow(
            Carbon::parse('2026-09-10 10:00:00')
        );

        $request = DocumentRequest::create([
            'request_number' => 'DR-DISABLED-0001',
            'status' => 'processing',
            'requested_at' => '2026-09-07 09:00:00',
        ]);

        $documentType = DocumentType::create([
            'name' => 'Transcript',
            'sla_enabled' => false,
            'sla_working_days' => 3,
        ]);

        DocumentRequestItem::create([
            'document_request_id' => $request->id,
            'document_type_id' => $documentType->id,
            'status' => DocumentRequestItemStatus::PROCESSING,
            'sla_started_at' => '2026-09-07 15:00:00',
            'sla_due_at' => '2026-09-09 15:00:00',
        ]);

        $query = DocumentRequest::query();

        $this->sla->applyRequestFilter(
            $query,
            DocumentRequestSlaService::FILTER_OVERDUE
        );

        $this->assertFalse(
            $query->whereKey($request->id)->exists()
        );
    }

    public function test_sla_filter_excludes_cancelled_and_unavailable_items(): void
    {
        Carbon::setTestNow(
            Carbon::parse('2026-09-10 10:00:00')
        );

        $cancelledRequest = DocumentRequest::create([
            'request_number' => 'DR-CANCELLED-0001',
            'status' => 'cancelled',
            'requested_at' => '2026-09-07 09:00:00',
        ]);

        $unavailableRequest = DocumentRequest::create([
            'request_number' => 'DR-UNAVAILABLE-0001',
            'status' => 'unavailable',
            'requested_at' => '2026-09-07 09:00:00',
        ]);

        $documentType = DocumentType::create([
            'name' => 'Form 137',
            'sla_enabled' => true,
            'sla_working_days' => 3,
        ]);

        DocumentRequestItem::create([
            'document_request_id' => $cancelledRequest->id,
            'document_type_id' => $documentType->id,
            'status' => DocumentRequestItemStatus::CANCELLED,
            'sla_started_at' => '2026-09-07 15:00:00',
            'sla_due_at' => '2026-09-09 15:00:00',
        ]);

        DocumentRequestItem::create([
            'document_request_id' => $unavailableRequest->id,
            'document_type_id' => $documentType->id,
            'status' => DocumentRequestItemStatus::UNAVAILABLE,
            'sla_started_at' => '2026-09-07 15:00:00',
            'sla_due_at' => '2026-09-09 15:00:00',
        ]);

        $query = DocumentRequest::query();

        $this->sla->applyRequestFilter(
            $query,
            DocumentRequestSlaService::FILTER_OVERDUE
        );

        $this->assertFalse(
            $query->whereKey($cancelledRequest->id)->exists()
        );

        $this->assertFalse(
            $query->whereKey($unavailableRequest->id)->exists()
        );
    }

    public function test_sla_filter_returns_request_when_at_least_one_item_matches(): void
    {
        Carbon::setTestNow(
            Carbon::parse('2026-09-10 10:00:00')
        );

        $request = DocumentRequest::create([
            'request_number' => 'DR-MIXED-0001',
            'status' => 'processing',
            'requested_at' => '2026-09-07 09:00:00',
        ]);

        $documentType = DocumentType::create([
            'name' => 'Form 137',
            'sla_enabled' => true,
            'sla_working_days' => 3,
        ]);

        DocumentRequestItem::create([
            'document_request_id' => $request->id,
            'document_type_id' => $documentType->id,
            'status' => DocumentRequestItemStatus::PROCESSING,
            'sla_started_at' => '2026-09-07 15:00:00',
            'sla_due_at' => '2026-09-09 15:00:00',
        ]);

        DocumentRequestItem::create([
            'document_request_id' => $request->id,
            'document_type_id' => $documentType->id,
            'status' => DocumentRequestItemStatus::PROCESSING,
            'sla_started_at' => '2026-09-10 15:00:00',
            'sla_due_at' => '2026-09-14 15:00:00',
        ]);

        $query = DocumentRequest::query();

        $this->sla->applyRequestFilter(
            $query,
            DocumentRequestSlaService::FILTER_OVERDUE
        );

        $this->assertTrue(
            $query->whereKey($request->id)->exists()
        );
    }

    public function test_invalid_sla_filter_does_not_modify_query(): void
    {
        $query = DocumentRequest::query();

        $sqlBefore = $query->toSql();

        $this->sla->applyRequestFilter(
            $query,
            'invalid'
        );

        $this->assertSame(
            $sqlBefore,
            $query->toSql()
        );
    }

    private function itemWithDocumentType(
        array $documentTypeAttributes,
        array $itemAttributes = []
    ): DocumentRequestItem {
        $item = new DocumentRequestItem($itemAttributes);

        $item->setRelation(
            'documentType',
            new DocumentType($documentTypeAttributes)
        );

        return $item;
    }

    protected function createTestTables(): void
    {
        Schema::dropIfExists('school_calendar_exceptions');

        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('sla_enabled')->default(false);
            $table->unsignedInteger('sla_working_days')->nullable();
            $table->timestamps();
        });

        Schema::create('document_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->nullable();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->string('student_record_type')->nullable();
            $table->string('legacy_first_name')->nullable();
            $table->string('legacy_middle_name')->nullable();
            $table->string('legacy_last_name')->nullable();
            $table->date('legacy_birth_date')->nullable();
            $table->unsignedBigInteger('requested_by_user_id')->nullable();
            $table->string('requested_by_name')->nullable();
            $table->string('requested_by_contact')->nullable();
            $table->string('requester_relationship')->nullable();
            $table->string('status')->nullable();
            $table->text('purpose')->nullable();
            $table->dateTime('requested_at')->nullable();
            $table->dateTime('processed_at')->nullable();
            $table->dateTime('released_at')->nullable();
            $table->unsignedBigInteger('processed_by_user_id')->nullable();
            $table->string('released_to_name')->nullable();
            $table->text('release_notes')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('document_request_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_request_id');
            $table->unsignedBigInteger('document_type_id');
            $table->string('status')->default('pending');
            $table->dateTime('sla_started_at')->nullable();
            $table->dateTime('sla_due_at')->nullable();
            $table->dateTime('sla_completed_at')->nullable();
            $table->dateTime('processing_started_at')->nullable();
            $table->unsignedBigInteger('processed_by_user_id')->nullable();
            $table->dateTime('ready_at')->nullable();
            $table->dateTime('released_at')->nullable();
            $table->string('released_to_name')->nullable();
            $table->text('release_notes')->nullable();
            $table->text('unavailable_reason')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index('document_request_id');
            $table->index('document_type_id');
            $table->index('sla_due_at');
        });
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
