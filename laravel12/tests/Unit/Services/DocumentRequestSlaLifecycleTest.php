<?php

namespace Tests\Unit\Services;

use App\Models\DocumentRequest;
use App\Models\DocumentRequestItem;
use App\Models\DocumentType;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\DocumentRequests\Services\DocumentRequestItemLifecycleService;
use Modules\DocumentRequests\Services\DocumentRequestLifecycleService;
use Tests\TestCase;

class DocumentRequestSlaLifecycleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createTestTables();
        Carbon::setTestNow('2026-09-07 09:00:00');
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        Schema::dropIfExists('document_request_item_status_histories');
        Schema::dropIfExists('document_request_status_histories');
        Schema::dropIfExists('document_request_items');
        Schema::dropIfExists('document_requests');
        Schema::dropIfExists('document_types');
        Schema::dropIfExists('school_calendar_exceptions');
        Schema::dropIfExists('school_settings');

        parent::tearDown();
    }

    public function test_parent_verification_starts_sla_for_eligible_items(): void
    {
        $request = $this->requestWithStatus('verification');
        $item = $this->itemFor($request, [
            'sla_enabled' => true,
            'sla_working_days' => 2,
        ]);

        app(DocumentRequestLifecycleService::class)->verify($request);

        $item->refresh();

        $this->assertSame(
            '2026-09-07 15:00:00',
            $item->sla_started_at->format('Y-m-d H:i:s')
        );
        $this->assertSame(
            '2026-09-08 15:00:00',
            $item->sla_due_at->format('Y-m-d H:i:s')
        );
    }

    public function test_parent_verification_leaves_disabled_sla_items_null(): void
    {
        $request = $this->requestWithStatus('verification');
        $item = $this->itemFor($request, [
            'sla_enabled' => false,
            'sla_working_days' => 2,
        ]);

        app(DocumentRequestLifecycleService::class)->verify($request);

        $item->refresh();

        $this->assertNull($item->sla_started_at);
        $this->assertNull($item->sla_due_at);
    }

    public function test_parent_verification_does_not_overwrite_sla_timestamps(): void
    {
        $request = $this->requestWithStatus('verification');
        $item = $this->itemFor($request, [
            'sla_enabled' => true,
            'sla_working_days' => 2,
        ], [
            'sla_started_at' => '2026-09-01 15:00:00',
            'sla_due_at' => '2026-09-02 15:00:00',
        ]);

        app(DocumentRequestLifecycleService::class)->verify($request);

        $item->refresh();

        $this->assertSame(
            '2026-09-01 15:00:00',
            $item->sla_started_at->format('Y-m-d H:i:s')
        );
        $this->assertSame(
            '2026-09-02 15:00:00',
            $item->sla_due_at->format('Y-m-d H:i:s')
        );
    }

    public function test_item_release_records_sla_completion(): void
    {
        $request = $this->requestWithStatus('ready_for_release');
        $item = $this->itemFor($request, [], [
            'status' => 'ready_for_release',
        ]);

        app(DocumentRequestItemLifecycleService::class)->release(
            $item,
            'Jane Doe'
        );

        $item->refresh();

        $this->assertSame('released', $item->status->value);
        $this->assertSame(
            '2026-09-07 09:00:00',
            $item->sla_completed_at->format('Y-m-d H:i:s')
        );
    }

    public function test_item_release_does_not_overwrite_sla_completion(): void
    {
        $request = $this->requestWithStatus('ready_for_release');
        $item = $this->itemFor($request, [], [
            'status' => 'ready_for_release',
            'sla_completed_at' => '2026-09-01 12:00:00',
        ]);

        app(DocumentRequestItemLifecycleService::class)->release(
            $item,
            'Jane Doe'
        );

        $item->refresh();

        $this->assertSame(
            '2026-09-01 12:00:00',
            $item->sla_completed_at->format('Y-m-d H:i:s')
        );
    }

    public function test_ready_for_release_does_not_set_sla_completion(): void
    {
        $request = $this->requestWithStatus('verified');
        $item = $this->itemFor($request, [], [
            'status' => 'processing',
        ]);

        app(DocumentRequestItemLifecycleService::class)->markReadyForRelease(
            $item
        );

        $item->refresh();

        $this->assertNull($item->sla_completed_at);
    }

    protected function requestWithStatus(string $status): DocumentRequest
    {
        return DocumentRequest::create([
            'request_number' => uniqid('DR-', true),
            'status' => $status,
        ]);
    }

    protected function itemFor(
        DocumentRequest $request,
        array $documentType = [],
        array $attributes = []
    ): DocumentRequestItem {
        $type = DocumentType::create(array_merge([
            'name' => uniqid('Document-', true),
            'sla_enabled' => true,
            'sla_working_days' => 3,
        ], $documentType));

        return DocumentRequestItem::create(array_merge([
            'document_request_id' => $request->id,
            'document_type_id' => $type->id,
            'status' => 'pending',
        ], $attributes));
    }

    protected function createTestTables(): void
    {
        Schema::dropIfExists('document_request_item_status_histories');
        Schema::dropIfExists('document_request_status_histories');
        Schema::dropIfExists('document_request_items');
        Schema::dropIfExists('document_requests');
        Schema::dropIfExists('document_types');
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
        });

        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('sla_enabled')->default(true);
            $table->unsignedInteger('sla_working_days')->default(3);
            $table->timestamps();
        });

        Schema::create('document_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique();
            $table->string('status');
            $table->timestamp('processed_at')->nullable();
            $table->unsignedBigInteger('processed_by_user_id')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->string('released_to_name')->nullable();
            $table->text('release_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('document_request_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_request_id');
            $table->unsignedBigInteger('document_type_id');
            $table->string('status');
            $table->timestamp('sla_started_at')->nullable();
            $table->timestamp('sla_due_at')->nullable();
            $table->timestamp('sla_completed_at')->nullable();
            $table->timestamp('processing_started_at')->nullable();
            $table->unsignedBigInteger('processed_by_user_id')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->string('released_to_name')->nullable();
            $table->text('release_notes')->nullable();
            $table->text('unavailable_reason')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('document_request_status_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_request_id');
            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->unsignedBigInteger('changed_by_user_id')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('document_request_item_status_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_request_item_id');
            $table->string('from_status');
            $table->string('to_status');
            $table->unsignedBigInteger('changed_by_user_id')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }
}
