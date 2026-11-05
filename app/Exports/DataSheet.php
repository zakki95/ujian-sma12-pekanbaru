<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DataSheet implements FromArray, WithHeadings, WithStyles, WithTitle
{
    public function array(): array
    {
        // Kosong - hanya header, tidak ada data dummy
        return [];
    }

    public function headings(): array
    {
        return ['no', 'nama_lengkap', 'email', 'nomor_induk'];
    }

    public function styles(Worksheet $sheet)
    {
        // 1. Paksa SELURUH kolom D (nomor_induk) menggunakan format TEKS '@'
        //    '@' adalah kode format Excel untuk "teks eksplisit"
        $sheet->getStyle('D')->getNumberFormat()->setFormatCode('@');

        // 2. Paksa header di D1 bertipe STRING secara eksplisit
        //    Ini menjamin PhpSpreadsheet menulis nilai sebagai string di level XML
        $sheet->getCell('D1')->setValueExplicit('nomor_induk', DataType::TYPE_STRING);

        // 3. Styling opsional: bold header
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Data';
    }
}