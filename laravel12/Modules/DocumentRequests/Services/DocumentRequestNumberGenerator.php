<?php

namespace Modules\DocumentRequests\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DocumentRequestNumberGenerator
{
    /**
     * Generate the next document request number for the given date.
     *
     * Format:
     * DR-YYYYMMDD-NNNN
     *
     * The sequence resets each calendar day.
     */
    public function generate(?Carbon $date = null): string
    {
        $date ??= now();

        $sequenceNumber = DB::transaction(function () use ($date) {
            $sequence = DB::table('document_request_sequences')
                ->where('sequence_date', $date->toDateString())
                ->lockForUpdate()
                ->first();

            if ($sequence === null) {
                DB::table('document_request_sequences')->insert([
                    'sequence_date' => $date->toDateString(),
                    'next_number' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                return 1;
            }

            $currentNumber = $sequence->next_number;

            DB::table('document_request_sequences')
                ->where('sequence_date', $date->toDateString())
                ->update([
                    'next_number' => $currentNumber + 1,
                    'updated_at' => now(),
                ]);

            return $currentNumber;
        });

        return sprintf(
            'DR-%s-%04d',
            $date->format('Ymd'),
            $sequenceNumber
        );
    }
}
