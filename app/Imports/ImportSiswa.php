<?php
namespace App\Imports;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class ImportSiswa implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;
    private bool $previewMode = false;
    public array $validRows = [];
    public array $errors = [];

    public function onlyPreview(): void { $this->previewMode = true; }
    public function getValidRows(): array { return $this->validRows; }
    public function getErrors(): array { return $this->errors; }

    public function model(array $row)
    {
        // Validasi manual format NIS
        if (!isset($row['nomor_induk']) || !preg_match('/^NIS-\d+$/', (string)$row['nomor_induk'])) {
            $this->errors[] = "Baris " . ($row['no'] ?? '?') . ": Format nomor_induk harus NIS-xxxxx";
            return null;
        }

        if ($this->previewMode) {
            $this->validRows[] = $row;
            return null; 
        }

        return new Pengguna([
            'nama_lengkap' => $row['nama_lengkap'],
            'email' => $row['email'],
            'kata_sandi' => Hash::make('password123'),
            'peran' => 'siswa',
            'nomor_induk' => $row['nomor_induk'],
            'status' => 'aktif',
        ]);
    }

    public function rules(): array {
        return [
            'no' => 'required|numeric',
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna,email',
            'nomor_induk' => 'required|string|unique:pengguna,nomor_induk',
        ];
    }
}