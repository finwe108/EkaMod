<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class Sf1Export implements WithEvents
{
    use Exportable;

    public function __construct(
        protected array $meta,
        protected array $students
    ) {}

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $this->fillHeader($sheet);
                [$maleStudents, $femaleStudents] = $this->splitStudentsBySex($this->students);
                $this->fillStudentBlock($sheet, $maleStudents, 11, 30);
                $this->fillStudentBlock($sheet, $femaleStudents, 32, 51);
                $this->fillSummary($sheet, $maleStudents, $femaleStudents);
            },
        ];
    }

    protected function fillHeader($sheet): void
    {
        $sheet->setCellValue('F4', $this->meta['school_id'] ?? '');
        $sheet->setCellValue('J4', $this->meta['region'] ?? '');
        $sheet->setCellValue('M4', $this->meta['division'] ?? '');
        $sheet->setCellValue('S4', $this->meta['district'] ?? '');

        $sheet->setCellValue('F6', $this->meta['school_name'] ?? '');
        $sheet->setCellValue('O6', $this->meta['school_year'] ?? '');
        $sheet->setCellValue('S6', $this->meta['grade_level'] ?? '');
        $sheet->setCellValue('V6', $this->meta['section'] ?? '');

        $sheet->setCellValue('I9', $this->formatDate($this->meta['first_friday_of_june'] ?? null));
    }

    protected function splitStudentsBySex(array $students): array
    {
        $male = [];
        $female = [];

        foreach ($students as $student) {
            $sex = strtolower((string) ($student['sex'] ?? ''));

            if ($sex === 'male') {
                $male[] = $student;
            } elseif ($sex === 'female') {
                $female[] = $student;
            }
        }

        usort($male, fn ($a, $b) => strcmp($a['sort_name'] ?? '', $b['sort_name'] ?? ''));
        usort($female, fn ($a, $b) => strcmp($a['sort_name'] ?? '', $b['sort_name'] ?? ''));

        return [$male, $female];
    }

    protected function fillStudentBlock($sheet, array $students, int $startRow, int $endRow): void
    {
        $maxRows = $endRow - $startRow + 1;

        for ($i = 0; $i < $maxRows; $i++) {
            $row = $startRow + $i;
            $student = $students[$i] ?? [];

            $sheet->setCellValue("B{$row}", $student['lrn'] ?? '');
            $sheet->setCellValue("C{$row}", $student['full_name_sf1'] ?? '');
            $sheet->setCellValue("G{$row}", $student['sex_short'] ?? '');
            $sheet->setCellValue("H{$row}", $this->formatDate($student['birth_date'] ?? null));
            $sheet->setCellValue("I{$row}", $student['age'] ?? '');
            $sheet->setCellValue("J{$row}", $student['birth_place'] ?? '');
            $sheet->setCellValue("K{$row}", $student['mother_tongue'] ?? '');
            $sheet->setCellValue("L{$row}", $student['ethnic_group'] ?? '');
            $sheet->setCellValue("M{$row}", $student['religion'] ?? '');
            $sheet->setCellValue("N{$row}", $student['house_street'] ?? '');
            $sheet->setCellValue("O{$row}", $student['barangay'] ?? '');
            $sheet->setCellValue("P{$row}", $student['municipality_city'] ?? '');
            $sheet->setCellValue("Q{$row}", $student['province'] ?? '');
            $sheet->setCellValue("R{$row}", $student['father_name'] ?? '');
            $sheet->setCellValue("T{$row}", $student['mother_name'] ?? '');
            $sheet->setCellValue("V{$row}", $student['guardian_name'] ?? '');
            $sheet->setCellValue("W{$row}", $student['guardian_relationship'] ?? '');
            $sheet->setCellValue("X{$row}", $student['parent_guardian_contact'] ?? '');
            $sheet->setCellValue("Y{$row}", $student['code'] ?? '');
            $sheet->setCellValue("Z{$row}", $student['required_info'] ?? '');
        }
    }

    protected function fillSummary($sheet, array $maleStudents, array $femaleStudents): void
    {
        $bosyMale = $this->meta['bosy_male'] ?? count($maleStudents);
        $bosyFemale = $this->meta['bosy_female'] ?? count($femaleStudents);
        $bosyTotal = $this->meta['bosy_total'] ?? ($bosyMale + $bosyFemale);

        $eosyMale = $this->meta['eosy_male'] ?? $bosyMale;
        $eosyFemale = $this->meta['eosy_female'] ?? $bosyFemale;
        $eosyTotal = $this->meta['eosy_total'] ?? ($eosyMale + $eosyFemale);

        $sheet->setCellValue('R54', $bosyMale);
        $sheet->setCellValue('R55', $bosyFemale);
        $sheet->setCellValue('R56', $bosyTotal);

        $sheet->setCellValue('S54', $eosyMale);
        $sheet->setCellValue('S55', $eosyFemale);
        $sheet->setCellValue('S56', $eosyTotal);

        $sheet->setCellValue('U54', $this->meta['adviser_name'] ?? '');
        $sheet->setCellValue('V56', $this->formatDate($this->meta['bosy_date'] ?? null));
        $sheet->setCellValue('X54', $this->meta['school_head_name'] ?? '');
        $sheet->setCellValue('Z56', $this->formatDate($this->meta['eosy_date'] ?? null));
    }

    protected function formatDate($value): string
    {
        if (empty($value)) {
            return '';
        }

        try {
            return Carbon::parse($value)->format('m/d/Y');
        } catch (\Throwable $e) {
            return (string) $value;
        }
    }
}