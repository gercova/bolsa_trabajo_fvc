<?php

namespace App\Imports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class AccountBalanceImport implements ToCollection, WithStartRow, WithChunkReading
{
    public int $importedCount = 0;
    public int $skippedCount  = 0;
    private ?int $lastSeenYear = null;

    private const MONTH_MAP = [
        'ENERO'      => '01',
        'FEBRERO'    => '02',
        'MARZO'      => '03',
        'ABRIL'      => '04',
        'MAYO'       => '05',
        'JUNIO'      => '06',
        'JULIO'      => '07',
        'AGOSTO'     => '08',
        'SETIEMBRE'  => '09',
        'SEPTIEMBRE' => '09',
        'OCTUBRE'    => '10',
        'NOVIEMBRE'  => '11',
        'DICIEMBRE'  => '12',
    ];

    /**
     * Start reading from row 1 to dynamically detect headers and skip non-data sections.
     */
    public function startRow(): int
    {
        return 1;
    }

    /**
     * Process 500 rows per chunk.
     */
    public function chunkSize(): int
    {
        return 500;
    }

    /**
     * Column mapping (0-indexed, columns A–J):
     *  [0]  A  → MES                → month
     *  [1]  B  → FECHA              → date
     *  [2]  C  → N° B/V             → receipt_number
     *  [3]  D  → CLIENTE            → client
     *  [4]  E  → DESCRIPCIÓN        → description
     *  [5]  F  → CATEGORÍA          → category
     *  [6]  G  → PROGRAMA (COD.)    → program_code
     *  [7]  H  → PROGRAMA (NOMBRE)  → program_name
     *  [8]  I  → MONTO (S/)         → amount
     *  [9]  J  → MOTIVO             → reason
     */
    public function collection(Collection $rows): void
    {
        $now   = now()->toDateTimeString();
        $batch = [];

        foreach ($rows as $row) {
            $monthRaw       = $this->cleanString($row[0] ?? null);
            $dateRaw        = $row[1] ?? null;
            $receiptNumber  = $this->cleanString($row[2] ?? null);
            $client         = $this->cleanString($row[3] ?? null);
            $description    = $this->cleanString($row[4] ?? null);
            $category       = $this->cleanString($row[5] ?? null);
            $programCode    = $this->cleanString($row[6] ?? null);
            $programName    = $this->cleanString($row[7] ?? null);
            $amount         = $this->parseDecimal($row[8] ?? null);
            $reason         = $this->cleanString($row[9] ?? null);

            // Skip header or metadata rows
            if ($this->isHeaderOrMetadataRow($monthRaw, $receiptNumber, $category, $client)) {
                $this->skippedCount++;
                continue;
            }

            // Skip rows without a valid amount or without client/category
            if ($amount === null || $amount <= 0) {
                $this->skippedCount++;
                continue;
            }

            // Skip summary rows (e.g. 'TOTAL', 'PROGRAMA', pivot headers)
            if ($this->isSummaryRow($monthRaw, $receiptNumber, $client, $category)) {
                $this->skippedCount++;
                continue;
            }

            $normalizedMonth = $this->normalizeMonth($monthRaw);
            $parsedDate      = $this->parseDate($dateRaw, $normalizedMonth);

            $batch[] = [
                'month'          => $normalizedMonth ?? $monthRaw,
                'date'           => $parsedDate,
                'receipt_number' => $receiptNumber,
                'client'         => $client,
                'description'    => $description,
                'category'       => $category ?? 'OTROS',
                'program_code'   => $programCode,
                'program_name'   => $programName,
                'amount'         => $amount,
                'reason'         => $reason,
                'created_at'     => $now,
                'updated_at'     => $now,
            ];
        }

        if (! empty($batch)) {
            DB::table('account_balances')->insert($batch);
            $this->importedCount += count($batch);
        }
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function cleanString(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        $str = trim((string) $value);
        return $str !== '' ? $str : null;
    }

    private function normalizeMonth(?string $month): ?string
    {
        if (! $month) {
            return null;
        }
        $upper = mb_strtoupper(trim($month), 'UTF-8');
        return array_key_exists($upper, self::MONTH_MAP) ? $upper : $month;
    }

    private function isHeaderOrMetadataRow(?string $month, ?string $receipt, ?string $category, ?string $client): bool
    {
        $mUpper = mb_strtoupper((string) $month, 'UTF-8');
        $cUpper = mb_strtoupper((string) $category, 'UTF-8');
        $rUpper = mb_strtoupper((string) $receipt, 'UTF-8');
        $clUpper = mb_strtoupper((string) $client, 'UTF-8');

        // Check if this row is the table header: "MES", "FECHA", "N° B/V", "CATEGORÍA"
        if ($mUpper === 'MES' || $cUpper === 'CATEGORÍA' || $cUpper === 'CATEGORIA' || $rUpper === 'N° B/V' || $clUpper === 'CLIENTE') {
            return true;
        }

        return false;
    }

    private function isSummaryRow(?string $month, ?string $receipt, ?string $client, ?string $category): bool
    {
        $mUpper = mb_strtoupper((string) $month, 'UTF-8');
        $rUpper = mb_strtoupper((string) $receipt, 'UTF-8');
        $cUpper = mb_strtoupper((string) $client, 'UTF-8');

        if (str_contains($mUpper, 'TOTAL') || str_contains($mUpper, 'PROGRAMA') || str_contains($mUpper, 'SUBTOTAL')) {
            return true;
        }

        if (str_contains($rUpper, 'TOTAL') || str_contains($cUpper, 'TOTAL')) {
            return true;
        }

        return false;
    }

    private function parseDecimal(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        $str = trim((string) $value);
        // Remove currency symbols (S/, S/., $, etc.) and spaces
        $str = preg_replace('/[^\d,\.\-]/', '', $str);

        if ($str === '' || $str === '-') {
            return null;
        }

        // Handle mixed separators: e.g. 1,234.50 vs 1.234,50
        if (str_contains($str, '.') && str_contains($str, ',')) {
            if (strrpos($str, '.') > strrpos($str, ',')) {
                // Period is decimal separator (1,234.50)
                $str = str_replace(',', '', $str);
            } else {
                // Comma is decimal separator (1.234,50)
                $str = str_replace('.', '', $str);
                $str = str_replace(',', '.', $str);
            }
        } elseif (str_contains($str, ',')) {
            // Comma as single decimal separator (245,00)
            $str = str_replace(',', '.', $str);
        }

        return is_numeric($str) ? (float) $str : null;
    }

    private function parseDate(mixed $value, ?string $month = null): ?string
    {
        $monthNum = '01';
        if ($month) {
            $mUpper = mb_strtoupper($month, 'UTF-8');
            $monthNum = self::MONTH_MAP[$mUpper] ?? '01';
        }

        if ($value === null || $value === '') {
            // Fallback to last seen year if available
            if ($this->lastSeenYear) {
                return sprintf('%04d-%02d-01', $this->lastSeenYear, $monthNum);
            }
            return null;
        }

        // 1. Numeric 4-digit Year (e.g. 2024, 2025, 2026)
        if (is_numeric($value) && (int) $value >= 1990 && (int) $value <= 2100) {
            $year = (int) $value;
            $this->lastSeenYear = $year;
            return sprintf('%04d-%02d-01', $year, $monthNum);
        }

        // 2. Numeric Excel OLE serial date (e.g. 45658 for year ~2025)
        if (is_numeric($value) && (float) $value >= 25000 && (float) $value <= 75000) {
            try {
                $dt = ExcelDate::excelToDateTimeObject((float) $value);
                $this->lastSeenYear = (int) $dt->format('Y');
                return $dt->format('Y-m-d');
            } catch (\Exception) {
                // Continue to string checks
            }
        }

        $str = trim((string) $value);

        // 3. String 4-digit Year
        if (preg_match('/^(\d{4})$/', $str, $matches)) {
            $year = (int) $matches[1];
            if ($year >= 1990 && $year <= 2100) {
                $this->lastSeenYear = $year;
                return sprintf('%04d-%02d-01', $year, $monthNum);
            }
        }

        // 4. Standard Date String Formats
        foreach (['d/m/Y', 'd-m-Y', 'd/m/y', 'Y-m-d', 'Y/m/d', 'm/d/Y', 'd/m/Y H:i:s', 'Y-m-d H:i:s'] as $fmt) {
            try {
                $date = Carbon::createFromFormat($fmt, $str);
                if ($date && $date->year >= 1990 && $date->year <= 2100) {
                    $this->lastSeenYear = $date->year;
                    return $date->format('Y-m-d');
                }
            } catch (\Exception) {
                // try next format
            }
        }

        // 5. Extract 4-digit year from string if present
        if (preg_match('/\b(20\d{2})\b/', $str, $matches)) {
            $year = (int) $matches[1];
            $this->lastSeenYear = $year;
            return sprintf('%04d-%02d-01', $year, $monthNum);
        }

        // 6. Last fallback
        if ($this->lastSeenYear) {
            return sprintf('%04d-%02d-01', $this->lastSeenYear, $monthNum);
        }

        return null;
    }
}
