<?php

namespace Modules\Employees\Services;

use App\Models\EmployeeIdCounter;

class EmployeeIdGenerator
{
    public static function generate($employmentDate, $departmentCode)
    {
        // Extract year
        $year = date('Y', strtotime($employmentDate));

        // Normalize department code (uppercase)
        $departmentCode = strtoupper($departmentCode);

        // Find or create counter
        $counter = EmployeeIdCounter::firstOrCreate(
            [
                'year' => $year,
                'department_code' => $departmentCode,
            ],
            [
                'last_number' => 0,
            ]
        );

        // Increment counter
        $counter->last_number += 1;
        $counter->save();

        // Format number (e.g., 0001)
        $number = str_pad($counter->last_number, 4, '0', STR_PAD_LEFT);

        // Final Employee ID format
        // Example: EMP-2026-HR-0001
        return "EMP-{$year}-{$departmentCode}-{$number}";
    }
}