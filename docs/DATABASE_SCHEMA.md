# DATABASE SCHEMA — Rancangan Awal

## Prinsip
- Nama tabel dan kolom dalam bahasa Indonesia, snake_case.
- Setiap tabel memiliki `id` (bigint, primary key, auto-increment).
- Setiap tabel memiliki `created_at` dan `updated_at` (timestamp).
- Soft delete (`deleted_at`) hanya pada tabel yang memerlukan audit penghapusan.
- Foreign key dengan `ON DELETE CASCADE` atau `ON DELETE RESTRICT` sesuai konteks.
- Indeks pada kolom yang sering difilter/diurutkan.
- Unique constraint pada kolom yang harus unik.

## Tabel Awal

### 1. pengguna
Menyimpan semua pengguna (admin, guru, siswa).

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | bigint | PK, auto-increment | |
| nama_lengkap | varchar(255) | NOT NULL | |
| email | varchar(255) | UNIQUE, NOT NULL | |
| email_verified_at | timestamp | NULLABLE | |
| kata_sandi | varchar(255) | NOT NULL | Hash bcrypt |
| peran | enum('admin','guru','siswa') | NOT NULL | |
| nomor_induk | varchar(50) | UNIQUE, NULLABLE | NIP untuk guru, NIS untuk siswa |
| telepon | varchar(20) | NULLABLE | |
| foto | varchar(255) | NULLABLE | Path ke Supabase Storage |
| status | enum('aktif','nonaktif') | NOT NULL, default 'aktif' | |
| created_at | timestamp | NOT NULL | |
| updated_at | timestamp | NOT NULL | |
| deleted_at | timestamp | NULLABLE | Soft delete |

**Indeks**: `email`, `nomor_induk`, `peran`, `status`.

---

### 2. kelas
Menyimpan data kelas.

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | bigint | PK, auto-increment | |
| nama_kelas | varchar(50) | UNIQUE, NOT NULL | Contoh: "X IPA 1" |
| tingkat | enum('X','XI','XII') | NOT NULL | |
| tahun_ajaran | varchar(20) | NOT NULL | Contoh: "2026/2027" |
| wali_kelas_id | bigint | FK → pengguna.id, NULLABLE | |
| created_at | timestamp | NOT NULL | |
| updated_at | timestamp | NOT NULL | |

**Indeks**: `nama_kelas`, `tingkat`, `tahun_ajaran`.

---

### 3. anggota_kelas
Relasi many-to-many antara siswa dan kelas.

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | bigint | PK, auto-increment | |
| kelas_id | bigint | FK → kelas.id, ON DELETE CASCADE | |
| pengguna_id | bigint | FK → pengguna.id, ON DELETE CASCADE | |
| status | enum('aktif','lulus','keluar') | NOT NULL, default 'aktif' | |
| created_at | timestamp | NOT NULL | |
| updated_at | timestamp | NOT NULL | |

**Unique constraint**: `(kelas_id, pengguna_id)`.
**Indeks**: `kelas_id`, `pengguna_id`.

---

### 4. mata_pelajaran
Menyimpan data mata pelajaran.

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | bigint | PK, auto-increment | |
| nama_mapel | varchar(100) | UNIQUE, NOT NULL | |
| kode_mapel | varchar(20) | UNIQUE, NOT NULL | |
| created_at | timestamp | NOT NULL | |
| updated_at | timestamp | NOT NULL | |

**Indeks**: `nama_mapel`, `kode_mapel`.

---

### 5. guru_mapel
Relasi many-to-many antara guru dan mata pelajaran.

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | bigint | PK, auto-increment | |
| pengguna_id | bigint | FK → pengguna.id, ON DELETE CASCADE | |
| mata_pelajaran_id | bigint | FK → mata_pelajaran.id, ON DELETE CASCADE | |
| created_at | timestamp | NOT NULL | |
| updated_at | timestamp | NOT NULL | |

**Unique constraint**: `(pengguna_id, mata_pelajaran_id)`.
**Indeks**: `pengguna_id`, `mata_pelajaran_id`.

---

### 6. bank_soal
Menyimpan soal individual.

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | bigint | PK, auto-increment | |
| guru_id | bigint | FK → pengguna.id, ON DELETE RESTRICT | |
| mata_pelajaran_id | bigint | FK → mata_pelajaran.id, ON DELETE RESTRICT | |
| jenis_soal | enum('pg','pg_kompleks','benar_salah','esai') | NOT NULL | |
| teks_soal | text | NOT NULL | |
| gambar_soal | varchar(255) | NULLABLE | Path ke Supabase Storage |
| kunci_jawaban | json | NULLABLE | Untuk PG, PG kompleks, B/S |
| rubrik_esai | text | NULLABLE | Untuk esai |
| bobot_nilai | decimal(5,2) | NOT NULL, default 1.00 | |
| tingkat_kesukaran | enum('mudah','sedang','sulit') | NULLABLE | |
| status | enum('draft','aktif','arsip') | NOT NULL, default 'draft' | |
| created_at | timestamp | NOT NULL | |
| updated_at | timestamp | NOT NULL | |
| deleted_at | timestamp | NULLABLE | Soft delete |

**Indeks**: `guru_id`, `mata_pelajaran_id`, `jenis_soal`, `status`.

---

### 7. pilihan_jawaban
Menyimpan opsi jawaban untuk soal PG, PG kompleks, B/S.

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | bigint | PK, auto-increment | |
| bank_soal_id | bigint | FK → bank_soal.id, ON DELETE CASCADE | |
| label | varchar(5) | NOT NULL | Contoh: "A", "B", "Benar" |
| teks_jawaban | text | NOT NULL | |
| gambar_jawaban | varchar(255) | NULLABLE | Path ke Supabase Storage |
| is_benar | boolean | NOT NULL, default false | |
| urutan | integer | NOT NULL | |
| created_at | timestamp | NOT NULL | |
| updated_at | timestamp | NOT NULL | |

**Indeks**: `bank_soal_id`, `urutan`.

---

### 8. berkas_soal
Menyimpan metadata berkas template Word yang diunggah.

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | bigint | PK, auto-increment | |
| guru_id | bigint | FK → pengguna.id, ON DELETE RESTRICT | |
| nama_berkas | varchar(255) | NOT NULL | |
| path_berkas | varchar(255) | NOT NULL | Path ke Supabase Storage |
| ukuran_berkas | bigint | NOT NULL | Dalam byte |
| mime_type | varchar(100) | NOT NULL | |
| status_impor | enum('unggah','validasi','pratinjau','selesai','gagal') | NOT NULL | |
| ringkasan_impor | json | NULLABLE | Jumlah berhasil, gagal, perlu perbaikan |
| created_at | timestamp | NOT NULL | |
| updated_at | timestamp | NOT NULL | |

**Indeks**: `guru_id`, `status_impor`.

---

### 9. paket_soal
Menyimpan kumpulan soal untuk ujian.

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | bigint | PK, auto-increment | |
| guru_id | bigint | FK → pengguna.id, ON DELETE RESTRICT | |
| mata_pelajaran_id | bigint | FK → mata_pelajaran.id, ON DELETE RESTRICT | |
| nama_paket | varchar(255) | NOT NULL | |
| deskripsi | text | NULLABLE | |
| durasi_menit | integer | NOT NULL | |
| status | enum('draft','aktif','arsip') | NOT NULL, default 'draft' | |
| created_at | timestamp | NOT NULL | |
| updated_at | timestamp | NOT NULL | |
| deleted_at | timestamp | NULLABLE | Soft delete |

**Indeks**: `guru_id`, `mata_pelajaran_id`, `status`.

---

### 10. anggota_paket_soal
Relasi many-to-many antara paket soal dan bank soal.

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | bigint | PK, auto-increment | |
| paket_soal_id | bigint | FK → paket_soal.id, ON DELETE CASCADE | |
| bank_soal_id | bigint | FK → bank_soal.id, ON DELETE RESTRICT | |
| urutan | integer | NOT NULL | |
| created_at | timestamp | NOT NULL | |
| updated_at | timestamp | NOT NULL | |

**Unique constraint**: `(paket_soal_id, bank_soal_id)`.
**Indeks**: `paket_soal_id`, `urutan`.

---

### 11. ujian
Menyimpan metadata ujian (event ujian).

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | bigint | PK, auto-increment | |
| nama_ujian | varchar(255) | NOT NULL | |
| deskripsi | text | NULLABLE | |
| tahun_ajaran | varchar(20) | NOT NULL | |
| semester | enum('ganjil','genap') | NOT NULL | |
| status | enum('draft','aktif','selesai','dibatalkan') | NOT NULL, default 'draft' | |
| created_at | timestamp | NOT NULL | |
| updated_at | timestamp | NOT NULL | |

**Indeks**: `tahun_ajaran`, `semester`, `status`.

---

### 12. jadwal_ujian
Menyimpan jadwal pelaksanaan ujian.

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | bigint | PK, auto-increment | |
| ujian_id | bigint | FK → ujian.id, ON DELETE CASCADE | |
| mata_pelajaran_id | bigint | FK → mata_pelajaran.id, ON DELETE RESTRICT | |
| paket_soal_id | bigint | FK → paket_soal.id, ON DELETE RESTRICT | |
| tanggal | date | NOT NULL | |
| waktu_mulai | time | NOT NULL | |
| waktu_selesai | time | NOT NULL | |
| ruang | varchar(50) | NULLABLE | |
| status | enum('terjadwal','berlangsung','selesai','dibatalkan') | NOT NULL, default 'terjadwal' | |
| created_at | timestamp | NOT NULL | |
| updated_at | timestamp | NOT NULL | |

**Indeks**: `ujian_id`, `mata_pelajaran_id`, `tanggal`, `status`.

---

### 13. pengawas_jadwal
Relasi many-to-many antara jadwal ujian dan pengawas (guru).

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | bigint | PK, auto-increment | |
| jadwal_ujian_id | bigint | FK → jadwal_ujian.id, ON DELETE CASCADE | |
| pengguna_id | bigint | FK → pengguna.id, ON DELETE CASCADE | |
| created_at | timestamp | NOT NULL | |
| updated_at | timestamp | NOT NULL | |

**Unique constraint**: `(jadwal_ujian_id, pengguna_id)`.
**Indeks**: `jadwal_ujian_id`, `pengguna_id`.

---

### 14. peserta_ujian
Menyimpan daftar peserta untuk jadwal ujian tertentu.

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | bigint | PK, auto-increment | |
| jadwal_ujian_id | bigint | FK → jadwal_ujian.id, ON DELETE CASCADE | |
| pengguna_id | bigint | FK → pengguna.id, ON DELETE CASCADE | |
| status | enum('terdaftar','belum_mulai','sedang_mengerjakan','selesai','dikecualikan') | NOT NULL, default 'terdaftar' | |
| created_at | timestamp | NOT NULL | |
| updated_at | timestamp | NOT NULL | |

**Unique constraint**: `(jadwal_ujian_id, pengguna_id)`.
**Indeks**: `jadwal_ujian_id`, `pengguna_id`, `status`.

---

### 15. sesi_ujian
Menyimpan sesi aktif siswa saat mengerjakan ujian.

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | bigint | PK, auto-increment | |
| peserta_ujian_id | bigint | FK → peserta_ujian.id, ON DELETE CASCADE | |
| token_ujian_id | bigint | FK → token_ujian.id, ON DELETE RESTRICT | |
| waktu_mulai | timestamp | NOT NULL | |
| waktu_selesai | timestamp | NULLABLE | |
| durasi_terpakai_detik | integer | NULLABLE | |
| status | enum('aktif','selesai','kedaluwarsa','dikunci') | NOT NULL, default 'aktif' | |
| ip_address | varchar(45) | NULLABLE | |
| user_agent | text | NULLABLE | |
| created_at | timestamp | NOT NULL | |
| updated_at | timestamp | NOT NULL | |

**Indeks**: `peserta_ujian_id`, `token_ujian_id`, `status`, `waktu_mulai`.

---

### 16. soal_ujian
Menyimpan soal yang disajikan dalam sesi ujian (snapshot dari bank_soal).

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | bigint | PK, auto-increment | |
| sesi_ujian_id | bigint | FK → sesi_ujian.id, ON DELETE CASCADE | |
| bank_soal_id | bigint | FK → bank_soal.id, ON DELETE RESTRICT | |
| urutan | integer | NOT NULL | |
| created_at | timestamp | NOT NULL | |
| updated_at | timestamp | NOT NULL | |

**Indeks**: `sesi_ujian_id`, `urutan`.

---

### 17. jawaban_siswa
Menyimpan jawaban siswa per soal.

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | bigint | PK, auto-increment | |
| sesi_ujian_id | bigint | FK → sesi_ujian.id, ON DELETE CASCADE | |
| soal_ujian_id | bigint | FK → soal_ujian.id, ON DELETE CASCADE | |
| jawaban | json | NULLABLE | Struktur tergantung jenis soal |
| waktu_jawab | timestamp | NULLABLE | |
| status_koreksi | enum('belum_dikoreksi','sudah_dikoreksi') | NOT NULL, default 'belum_dikoreksi' | Untuk esai |
| nilai_diperoleh | decimal(5,2) | NULLABLE | |
| catatan_koreksi | text | NULLABLE | |
| dikoreksi_oleh | bigint | FK → pengguna.id, NULLABLE | |
| created_at | timestamp | NOT NULL | |
| updated_at | timestamp | NOT NULL | |

**Unique constraint**: `(sesi_ujian_id, soal_ujian_id)`.
**Indeks**: `sesi_ujian_id`, `soal_ujian_id`, `status_koreksi`.

---

### 18. token_ujian
Menyimpan token ujian yang berganti setiap 5 menit.

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | bigint | PK, auto-increment | |
| jadwal_ujian_id | bigint | FK → jadwal_ujian.id, ON DELETE CASCADE | |
| token | varchar(64) | UNIQUE, NOT NULL | |
| waktu_mulai | timestamp | NOT NULL | |
| waktu_berakhir | timestamp | NOT NULL | |
| status | enum('aktif','kedaluwarsa','dibatalkan') | NOT NULL, default 'aktif' | |
| dibuat_oleh | bigint | FK → pengguna.id, NULLABLE | NULL jika otomatis |
| created_at | timestamp | NOT NULL | |
| updated_at | timestamp | NOT NULL | |

**Indeks**: `jadwal_ujian_id`, `token`, `waktu_mulai`, `waktu_berakhir`, `status`.

---

### 19. log_pelanggaran
Menyimpan kejadian pelanggaran (halaman tersembunyi, dll).

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | bigint | PK, auto-increment | |
| sesi_ujian_id | bigint | FK → sesi_ujian.id, ON DELETE CASCADE | |
| jenis_pelanggaran | varchar(100) | NOT NULL | Contoh: "halaman_tersembunyi" |
| deskripsi | text | NULLABLE | |
| durasi_detik | integer | NULLABLE | |
| tindakan | enum('peringatan','dikunci','dibuka_kembali') | NULLABLE | |
| ditindak_oleh | bigint | FK → pengguna.id, NULLABLE | |
| created_at | timestamp | NOT NULL | |

**Indeks**: `sesi_ujian_id`, `jenis_pelanggaran`, `created_at`.

---

### 20. notifikasi_pengawas
Menyimpan notifikasi untuk pengawas.

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | bigint | PK, auto-increment | |
| pengawas_id | bigint | FK → pengguna.id, ON DELETE CASCADE | |
| sesi_ujian_id | bigint | FK → sesi_ujian.id, ON DELETE CASCADE | |
| jenis_notifikasi | varchar(100) | NOT NULL | |
| pesan | text | NOT NULL | |
| status_baca | boolean | NOT NULL, default false | |
| created_at | timestamp | NOT NULL | |

**Indeks**: `pengawas_id`, `status_baca`, `created_at`.

---

### 21. hasil_ujian
Menyimpan hasil akhir ujian per peserta.

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | bigint | PK, auto-increment | |
| peserta_ujian_id | bigint | FK → peserta_ujian.id, ON DELETE CASCADE | |
| nilai_objektif | decimal(5,2) | NULLABLE | |
| nilai_esai | decimal(5,2) | NULLABLE | |
| nilai_akhir | decimal(5,2) | NULLABLE | |
| status_nilai | enum('sementara','final') | NOT NULL, default 'sementara' | |
| dipublikasikan | boolean | NOT NULL, default false | |
| dipublikasikan_oleh | bigint | FK → pengguna.id, NULLABLE | |
| waktu_publikasi | timestamp | NULLABLE | |
| created_at | timestamp | NOT NULL | |
| updated_at | timestamp | NOT NULL | |

**Unique constraint**: `peserta_ujian_id`.
**Indeks**: `peserta_ujian_id`, `status_nilai`, `dipublikasikan`.

---

### 22. rincian_nilai
Menyimpan rincian nilai per jenis soal (untuk laporan).

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | bigint | PK, auto-increment | |
| hasil_ujian_id | bigint | FK → hasil_ujian.id, ON DELETE CASCADE | |
| jenis_soal | enum('pg','pg_kompleks','benar_salah','esai') | NOT NULL | |
| jumlah_soal | integer | NOT NULL | |
| jumlah_benar | integer | NOT NULL | |
| bobot | decimal(5,2) | NOT NULL | |
| nilai_diperoleh | decimal(5,2) | NOT NULL | |
| created_at | timestamp | NOT NULL | |
| updated_at | timestamp | NOT NULL | |

**Indeks**: `hasil_ujian_id`, `jenis_soal`.

---

### 23. audit_log
Menyimpan log audit untuk semua aksi penting.

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| id | bigint | PK, auto-increment | |
| pengguna_id | bigint | FK → pengguna.id, NULLABLE | NULL jika sistem |
| aksi | varchar(100) | NOT NULL | Contoh: "login", "ubah_nilai" |
| model_type | varchar(255) | NULLABLE | |
| model_id | bigint | NULLABLE | |
| deskripsi | text | NULLABLE | |
| data_lama | json | NULLABLE | |
| data_baru | json | NULLABLE | |
| ip_address | varchar(45) | NULLABLE | |
| user_agent | text | NULLABLE | |
| created_at | timestamp | NOT NULL | |

**Indeks**: `pengguna_id`, `aksi`, `model_type`, `model_id`, `created_at`.

---

## Relasi Antar Tabel
## Status Implementasi (Tahap 04)

- **Tanggal Implementasi**: 31 Oktober 2026
- **Status**: ✅ SELESAI - Semua 16 tabel berhasil dibuat di Supabase PostgreSQL
- **Metode Koneksi**: Transaction Pooler (Port 6543)
- **Project ID**: hifpvusnigugjbwshstz
- **Region**: Singapore (ap-southeast-1)

### Jaminan Integritas & Konkurensi (Database Level)

1. **Satu Sesi Aktif per Peserta**:  
   Tabel `sesi_ujian` memiliki **Partial Unique Index** pada kolom `peserta_ujian_id` dengan kondisi `WHERE status = 'aktif'`. Ini mencegah race condition saat siswa mencoba membuka banyak tab atau request konkuren.

2. **Jawaban Unik per Soal per Sesi**:  
   Tabel `jawaban_siswa` memiliki **Unique Constraint** pada kombinasi `(sesi_ujian_id, soal_ujian_id)`. Ini menjamin bahwa autosave atau submit jawaban yang tumpang tindih akan di-handle aman oleh database.

3. **Keamanan Penghapusan (Cascading & Restrict)**:  
   - `ON DELETE CASCADE`: Untuk data turunan langsung (misal: `soal_ujian` dihapus jika `sesi_ujian` dihapus).
   - `ON DELETE RESTRICT`: Untuk data referensi kritis (misal: `bank_soal` tidak dapat dihapus jika masih digunakan dalam `soal_ujian`).