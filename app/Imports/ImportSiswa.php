<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ImportSiswa implements WithMultipleSheets
{
    public bool $previewMode = false;
    public array $duplicateNis = [];
    public array $allErrors = [];
    public array $validRows = [];

    public ImportSiswaDataSheet $dataSheet;

    public function __construct()
    {
        $this->dataSheet = new ImportSiswaDataSheet($this);
    }

    public function sheets(): array
    {
        return [
            'Data' => $this->dataSheet,
        ];
    }
}