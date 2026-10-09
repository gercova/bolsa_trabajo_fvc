<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class UserTemplateExport implements WithMultipleSheets
{
    /**
     * Return array of sheets for the user import template.
     *
     * Sheet 1: UserDataSheetExport (data entry sheet with headers and examples)
     * Sheet 2: UserInstructionsSheetExport (guidelines, rules, and specifications)
     */
    public function sheets(): array
    {
        return [
            new UserDataSheetExport,
            new UserInstructionsSheetExport,
        ];
    }
}
