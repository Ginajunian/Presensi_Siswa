<?php

namespace App\Exports;

use App\Exports\Sheets\DaftarKelasSheet;
use App\Exports\Sheets\TemplateSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SiswaImportTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new TemplateSheet(),
            new DaftarKelasSheet(),
        ];
    }
}