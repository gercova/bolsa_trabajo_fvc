<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CertificateTemplateExport implements WithMultipleSheets
{
    /**
     * Return array of sheets for the certificate import template.
     *
     * Sheet 1: CertificateDataSheetExport (the actual data entry sheet with headers and examples)
     * Sheet 2: CertificateInstructionsSheetExport (comprehensive guide, column definitions, and rules)
     */
    public function sheets(): array
    {
        return [
            new CertificateDataSheetExport,
            new CertificateInstructionsSheetExport,
        ];
    }
}
