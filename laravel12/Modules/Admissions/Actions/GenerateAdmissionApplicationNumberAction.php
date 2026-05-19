<?php

namespace Modules\Admissions\Actions;

use App\Models\AdmissionApplication;
use Illuminate\Support\Str;

/**
 * Generates unique admission application numbers.
 *
 * Module: Admissions
 * Layer: Action
 */
class GenerateAdmissionApplicationNumberAction
{
    /**
     * Generate a unique application number.
     *
     * Format:
     * ADM-YYYYMMDD-XXXXXX
     *
     * @return string
     */
    public function execute(): string
    {
        do {
            $number = 'ADM-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (AdmissionApplication::where('application_number', $number)->exists());

        return $number;
    }
}