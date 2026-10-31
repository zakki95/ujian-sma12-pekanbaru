# TESTING — Strategi Pengujian

## Prinsip
- Setiap fitur harus memiliki pengujian otomatis.
- Pengujian harus dapat dijalankan berulang dengan hasil yang konsisten.
- Pengujian tidak boleh bergantung pada data eksternal atau layanan pihak ketiga (kecuali Supabase untuk integrasi).
- Pengujian harus cepat (< 5 menit untuk full suite).

## Jenis Pengujian

### 1. Unit Testing
- Menguji logika bisnis individual (model, service, helper).
- Menggunakan **PHPUnit** (atau **Pest** jika disepakati).
- Mocking dependency eksternal (database, storage, API).
- Target cakupan: **80%+** untuk logika kritis.

**Contoh**:
- Perhitungan nilai objektif.
- Validasi token ujian.
- Parsing template Word.

### 2. Feature Testing
- Menguji endpoint HTTP (request → response).
- Menggunakan **PHPUnit** + **Laravel HTTP testing**.
- Database di-reset setiap tes (menggunakan `RefreshDatabase`).
- Menguji autentikasi, otorisasi, validasi, dan respons.

**Contoh**:
- Login dengan peran berbeda.
- Akses endpoint dengan hak akses yang berbeda.
- Impor soal dari template Word.
- Autosave jawaban.

### 3. Browser Testing
- Menguji alur pengguna secara end-to-end di browser.
- Menggunakan **Laravel Dusk**.
- Menguji alur kritis: login, mengerjakan ujian, autosave, deteksi kecurangan.

**Contoh**:
- Siswa login dan mengerjakan ujian.
- Guru mengimpor soal dan melihat pratinjau.
- Pengawas melihat notifikasi pelanggaran.

### 4. Load Testing
- Menguji kapasitas sistem dengan 1.200 siswa serentak.
- Menggunakan **k6** atau **JMeter**.
- Skenario:
  - 1.200 siswa login bersamaan.
  - 1.200 siswa memulai ujian bersamaan.
  - 1.200 siswa autosave jawaban setiap 10 detik selama 90 menit.
  - 1.200 siswa submit ujian bersamaan.
- Metrik:
  - Response time (p95 < 2 detik).
  - Error rate (< 1%).
  - Throughput (request per detik).

**Target**:
- Sistem dapat menangani 1.200 siswa serentak tanpa degradasi performa.
- Autosave tidak kehilangan data.
- Timer server konsisten.

### 5. Security Testing
- Menguji kerentanan umum (SQL injection, XSS, CSRF, dll).
- Menggunakan **OWASP ZAP** atau **Laravel Security Scanner**.
- Menguji hak akses (authorization bypass).
- Menguji validasi input.

**Contoh**:
- Siswa tidak dapat mengakses kunci jawaban.
- Siswa tidak dapat mengubah nilai sendiri.
- Guru tidak dapat mengakses data guru lain.

### 6. Manual Testing
- UAT (User Acceptance Testing) dengan pengguna nyata.
- Checklist pengujian manual untuk alur kritis.
- Pelaporan bug dan feedback.

## Struktur Folder Pengujian
