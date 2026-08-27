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

    /**
     * Data rows begin at row 2.
     * Row 1 is the header row:
     *   A = MES, B = FECHA, C = N° B/V, D = CLIENTE,
     *   E = DESCRIPCIÓN, F = CATEGORÍA, G = PROGRAMA (COD.),
     *   H = PROGRAMA (NOMBRE), I = MONTO (S/), J = MOTIVO
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * Process 500 rows per chunk → single INSERT per chunk.
     */
    public function chunkSize(): int
    {
        return 500;
    }

    /**
     * Column mapping (0-indexed, columns A–J):
     *
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
            // Skip if both receipt_number and client are empty (blank row)
            $receiptNumber = $this->cleanString($row[2] ?? null);
            $client        = $this->cleanString($row[3] ?? null);

            if (empty($receiptNumber) && empty($client)) {
                $this->skippedCount++;
                continue;
            }

            $batch[] = [
                'month'          => $this->cleanString($row[0] ?? null),
                'date'           => $this->parseDate($row[1]  ?? null),
                'receipt_number' => $receiptNumber,
                'client'         => $client,
                'description'    => $this->cleanString($row[4] ?? null),
                'category'       => $this->cleanString($row[5] ?? null),
                'program_code'   => $this->cleanString($row[6] ?? null),
                'program_name'   => $this->cleanString($row[7] ?? null),
                'amount'         => $this->parseDecimal($row[8] ?? null),
                'reason'         => $this->cleanString($row[9] ?? null),
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

    private function parseDecimal(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        // Remove currency symbols and thousand separators
        $clean = preg_replace('/[^\d.\-]/', '', (string) $value);
        return is_numeric($clean) ? $clean : null;
    }

    private function parseDate(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Excel OLE numeric serial
        if (is_numeric($value)) {
            try {
                return ExcelDate::excelToDateTimeObject((float) $value)->format('Y-m-d');
            } catch (\Exception) {
                return null;
            }
        }

        $str = trim((string) $value);

        foreach (['d/m/Y', 'd-m-Y', 'd/m/y', 'Y-m-d', 'Y/m/d', 'm/d/Y', 'd/m/Y H:i:s', 'Y-m-d H:i:s'] as $fmt) {
            try {
                $date = Carbon::createFromFormat($fmt, $str);
                if ($date && $date->year > 1900 && $date->year < 2100) {
                    return $date->format('Y-m-d');
                }
            } catch (\Exception) {
                // try next format
            }
        }

        return null;
    }
}
