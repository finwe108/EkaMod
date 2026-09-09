<?php

namespace Tests\Unit\Models;

use App\Models\DocumentRequestItem;
use Carbon\Carbon;
use Tests\TestCase;

class DocumentRequestItemTest extends TestCase
{
    public function test_sla_timestamps_are_mass_assignable_datetime_casts(): void
    {
        $timestamps = [
            'sla_started_at' => '2026-09-07 09:00:00',
            'sla_due_at' => '2026-09-10 15:00:00',
            'sla_completed_at' => '2026-09-09 11:30:00',
        ];

        $item = new DocumentRequestItem($timestamps);

        foreach ($timestamps as $attribute => $value) {
            $this->assertInstanceOf(Carbon::class, $item->{$attribute});
            $this->assertSame($value, $item->{$attribute}->format('Y-m-d H:i:s'));
        }
    }
}
