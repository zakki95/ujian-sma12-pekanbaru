<?php

namespace App\Imports;

use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class ImportSiswaDataSheet implements OnEachRow, WithHeadingRow
{
    private ImportSiswa $parent;

    // Whitelist constraint PostgreSQL yang sudah diverifikasi dari migration
    private array $verifiedConstraints = [
        'pengguna_email_unique' => null,      // akan diisi dinamis dengan email
        'pengguna_nomor_induk_unique' => null, // akan diisi dinamis dengan NIS
    ];

    public function __construct(ImportSiswa $parent)
    {
        $this->parent = $parent;
    }

    public function onRow(Row $row)
    {
        $rowNumber = $row->getIndex();
        $data = $row->toArray();

        // ═══════════════════════════════════════════════════════
        // EKSTRAKSI NILAI DENGAN TIPE-AMAN
        // ═══════════════════════════════════════════════════════
        $nisRaw = $data['nomor_induk'] ?? null;
        $emailRaw = $data['email'] ?? null;
        $namaRaw = $data['nama_lengkap'] ?? null;
        $noRaw = $data['no'] ?? null;

        $nis = is_string($nisRaw) ? trim($nisRaw) : (is_numeric($nisRaw) ? trim((string) $nisRaw) : '');
        $email = is_string($emailRaw) ? trim($emailRaw) : (is_numeric($emailRaw) ? trim((string) $emailRaw) : '');
        $no = $noRaw;

        $errorsForThisRow = [];

        // ═══════════════════════════════════════════════════════
        // ATURAN 1 & 2: Kolom 'no' wajib & numerik
        // ═══════════════════════════════════════════════════════
        if ($no === null || $no === '') {
            $errorsForThisRow[] = "Nomor urut (no) wajib diisi.";
        } elseif (!is_numeric($no)) {
            $errorsForThisRow[] = "Nomor urut (no) harus berupa angka.";
        }

        // ═══════════════════════════════════════════════════════
        // ATURAN 3, 4, 5: Nama lengkap - strict string, max 255
        // ═══════════════════════════════════════════════════════
        if (!is_string($namaRaw)) {
            $errorsForThisRow[] = "Tipe data nama lengkap tidak valid (harus berupa teks).";
        } else {
            $nama = trim($namaRaw);
            if ($nama === '') {
                $errorsForThisRow[] = "Nama lengkap wajib diisi.";
            } elseif (mb_strlen($nama, 'UTF-8') > 255) {
                $errorsForThisRow[] = "Nama lengkap tidak boleh lebih dari 255 karakter.";
            }
        }

        // ═══════════════════════════════════════════════════════
        // ATURAN 6 & 7: Email wajib & format valid
        // ═══════════════════════════════════════════════════════
        if ($email === '') {
            $errorsForThisRow[] = "Email wajib diisi.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorsForThisRow[] = "Format email tidak valid.";
        }

        // ═══════════════════════════════════════════════════════
        // ATURAN 8 & 9: Nomor induk wajib & format regex
        // ═══════════════════════════════════════════════════════
        if ($nis === '') {
            $errorsForThisRow[] = "Nomor induk wajib diisi.";
        } elseif (!preg_match('/^(?:NIS-)?\d+$/', $nis)) {
            $errorsForThisRow[] = "Format nomor induk tidak valid (harus angka saja atau diawali NIS-).";
        }

        // ═══════════════════════════════════════════════════════
        // ATURAN 10: Duplikat intra-file (dari pre-scan)
        // ═══════════════════════════════════════════════════════
        if ($nis !== '' && in_array($nis, $this->parent->duplicateNis, true)) {
            $errorsForThisRow[] = "Nomor induk '{$nis}' terdeteksi duplikat dalam file.";
        }

        // ═══════════════════════════════════════════════════════
        // ATURAN 11: Email unik di database (query terpisah)
        // ═══════════════════════════════════════════════════════
        $emailFormatValid = ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL));
        if ($emailFormatValid) {
            $emailExistsInDb = Pengguna::where('email', $email)->exists();
            if ($emailExistsInDb) {
                $errorsForThisRow[] = "Email '{$email}' sudah terdaftar di database.";
            }
        }

        // ═══════════════════════════════════════════════════════
        // ATURAN 12: Nomor induk unik di database (query terpisah)
        // ═══════════════════════════════════════════════════════
        $nisFormatValid = ($nis !== '' && preg_match('/^(?:NIS-)?\d+$/', $nis));
        $nisNotDuplicateInFile = ($nis !== '' && !in_array($nis, $this->parent->duplicateNis, true));

        if ($nisFormatValid && $nisNotDuplicateInFile) {
            $nisExistsInDb = Pengguna::where('nomor_induk', $nis)->exists();
            if ($nisExistsInDb) {
                $errorsForThisRow[] = "Nomor induk '{$nis}' sudah terdaftar di database.";
            }
        }

        // ═══════════════════════════════════════════════════════
        // KEPUTUSAN: SIMPAN ATAU KUMPULKAN ERROR
        // ═══════════════════════════════════════════════════════
        if (empty($errorsForThisRow)) {
            if ($this->parent->previewMode) {
                $this->parent->validRows[] = $data;
            } else {
                // Simpan dengan penanganan race condition PostgreSQL
                try {
                    Pengguna::create([
                        'nama_lengkap' => $nama,
                        'email' => $email,
                        'kata_sandi' => Hash::make('password123'),
                        'peran' => 'siswa',
                        'nomor_induk' => $nis,
                        'status' => 'aktif',
                    ]);
                } catch (QueryException $e) {
                    $sqlState = (string) $e->getCode();
                    $message = $e->getMessage();

                    if ($sqlState === '23505') {
                        // Ekstrak nama constraint dari pesan error PostgreSQL
                        preg_match('/constraint "(.*?)"/', $message, $matches);
                        $constraintName = $matches[1] ?? null;

                        if ($constraintName === 'pengguna_email_unique') {
                            $errorsForThisRow[] = "Email '{$email}' bentrok dengan data lain saat penyimpanan.";
                        } elseif ($constraintName === 'pengguna_nomor_induk_unique') {
                            $errorsForThisRow[] = "Nomor induk '{$nis}' bentrok dengan data lain saat penyimpanan.";
                        } else {
                            // Constraint tidak dikenali - lempar exception
                            throw $e;
                        }
                    } else {
                        // Kesalahan sistem lain - lempar exception
                        throw $e;
                    }
                }

                // Jika catch menambahkan error, catat ke allErrors
                if (!empty($errorsForThisRow)) {
                    foreach ($errorsForThisRow as $err) {
                        $this->parent->allErrors[] = "Baris Excel ke-{$rowNumber}: {$err}";
                    }
                }
            }
        } else {
            // Ada error validasi - kumpulkan SEMUA error untuk baris ini
            foreach ($errorsForThisRow as $err) {
                $this->parent->allErrors[] = "Baris Excel ke-{$rowNumber}: {$err}";
            }
        }
    }
}