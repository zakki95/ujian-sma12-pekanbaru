<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class ImportSiswaScanDataSheet implements OnEachRow, WithHeadingRow
{
    public array $scannedRows = [];

    public function onRow(Row $row)
    {
        $data = $row->toArray();
        $nis = trim((string) ($data['nomor_induk'] ?? ''));

        // Abaikan baris dengan nomor induk kosong
        if ($nis !== '') {
            $this->scannedRows[] = [
                'row_number' => $row->getIndex(),
                'nomor_induk' => $nis,
            ];
        }
    }
}