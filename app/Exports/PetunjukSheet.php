<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

class PetunjukSheet implements FromArray, WithTitle
{
    public function array(): array
    {
        return [
            ['PANDUAN PENGISIAN TEMPLATE IMPOR SISWA'],
            [''],
            ['1. JANGAN mengubah nama header kolom (no, nama_lengkap, email, nomor_induk).'],
            ['2. Mulai isi data siswa pada baris ke-2 (tepat di bawah header).'],
            ['3. Format nomor_induk: angka saja (cth: 12345, 0012345) atau dengan awalan NIS- (cth: NIS-12345).'],
            ['4. PENTING: Pastikan kolom nomor_induk diformat sebagai TEKS di Excel agar angka nol di depan tidak hilang.'],
            ['5. Nomor induk dan email harus unik (tidak boleh ada duplikat di file ini maupun di database).'],
            ['6. Hapus baris panduan ini sebelum menyimpan file untuk diunggah.'],
        ];
    }

    public function title(): string
    {
        return 'Petunjuk';
    }
}