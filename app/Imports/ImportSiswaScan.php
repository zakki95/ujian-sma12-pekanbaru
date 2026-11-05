<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ImportSiswaScan implements WithMultipleSheets
{
    public ImportSiswaScanDataSheet $dataSheet;

    public function __construct()
    {
        $this->dataSheet = new ImportSiswaScanDataSheet();
    }

    public function sheets(): array
    {
        return [
            'Data' => $this->dataSheet,
        ];
    }
}