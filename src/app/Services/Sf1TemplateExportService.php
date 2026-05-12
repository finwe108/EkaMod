<?php

namespace App\Services;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Sf1TemplateExportService
{
    public function export(string $templatePath, string $outputPath, array $meta, array $students): string
    {

        $spreadsheet = IOFactory::load($templatePath);


        $sheet = $spreadsheet->getActiveSheet();

        $this->fillHeader($sheet, $meta);

        [$maleStudents, $femaleStudents] = $this->splitStudentsBySex($students);


        if (count($maleStudents) > 20 || count($femaleStudents) > 20) {
            throw new \RuntimeException('SF1 template supports only 20 male and 20 female learners per page.');
        }

        \Log::info('SF1 template sheet check', [
            'active_sheet' => $sheet->getTitle(),
            'highest_row' => $sheet->getHighestRow(),
            'highest_column' => $sheet->getHighestColumn(),
            'sample_B11_before' => $sheet->getCell('B11')->getValue(),
            'sample_C11_before' => $sheet->getCell('C11')->getValue(),
        ]);

        $this->fillStudentBlock($sheet, $maleStudents, 11, 30);
        $this->fillStudentBlock($sheet, $femaleStudents, 32, 51);
        $this->fillSummary($sheet, $meta, $maleStudents, $femaleStudents);

        \Log::info('SF1 filled cells check', [
            'sample_F4' => $sheet->getCell('F4')->getValue(),
            'sample_F6' => $sheet->getCell('F6')->getValue(),
            'sample_B11_after' => $sheet->getCell('B11')->getValue(),
            'sample_C11_after' => $sheet->getCell('C11')->getValue(),
            'sample_G11_after' => $sheet->getCell('G11')->getValue(),
        ]);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        /*
        |--------------------------------------------------------------------------
        | Prevent slow formula recalculation
        |--------------------------------------------------------------------------
        */
        if (method_exists($writer, 'setPreCalculateFormulas')) {
            $writer->setPreCalculateFormulas(false);
        }

        $writer->save($outputPath);

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        return $outputPath;
    }

    protected function fillHeader($sheet, array $meta): void
    {
        $sheet->setCellValue('F4', $meta['school_id'] ?? '');
        $sheet->setCellValue('J4', $meta['region'] ?? '');
        $sheet->setCellValue('M4', $meta['division'] ?? '');
        $sheet->setCellValue('S4', $meta['district'] ?? '');

        $sheet->setCellValue('F6', $meta['school_name'] ?? '');
        $sheet->setCellValue('O6', $meta['school_year'] ?? '');
        $sheet->setCellValue('S6', $meta['grade_level'] ?? '');
        $sheet->setCellValue('V6', $meta['section'] ?? '');

        $sheet->setCellValue('I9', $this->formatDate($meta['first_friday_of_june'] ?? null));
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

    protected function fillSummary($sheet, array $meta, array $maleStudents, array $femaleStudents): void
    {
        $bosyMale = $meta['bosy_male'] ?? count($maleStudents);
        $bosyFemale = $meta['bosy_female'] ?? count($femaleStudents);
        $bosyTotal = $meta['bosy_total'] ?? ($bosyMale + $bosyFemale);

        $eosyMale = $meta['eosy_male'] ?? $bosyMale;
        $eosyFemale = $meta['eosy_female'] ?? $bosyFemale;
        $eosyTotal = $meta['eosy_total'] ?? ($eosyMale + $eosyFemale);

        $sheet->setCellValue('R54', $bosyMale);
        $sheet->setCellValue('R55', $bosyFemale);
        $sheet->setCellValue('R56', $bosyTotal);

        $sheet->setCellValue('S54', $eosyMale);
        $sheet->setCellValue('S55', $eosyFemale);
        $sheet->setCellValue('S56', $eosyTotal);

        $sheet->setCellValue('U54', $meta['adviser_name'] ?? '');
        $sheet->setCellValue('V56', $this->formatDate($meta['bosy_date'] ?? null));
        $sheet->setCellValue('X54', $meta['school_head_name'] ?? '');
        $sheet->setCellValue('Z56', $this->formatDate($meta['eosy_date'] ?? null));
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