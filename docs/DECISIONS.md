# DECISIONS — Keputusan Proyek

## Keputusan Tetap (Tidak Berubah Tanpa Persetujuan)

### D-01: Peran Pengguna
Tiga peran: Admin, Guru, Siswa. Tidak ada peran tambahan.

### D-02: Teknologi Inti
- Laravel (versi stabil, TBD).
- Blade + Livewire + Tailwind CSS.
- Supabase PostgreSQL.
- Supabase Storage (privat).
- GitHub privat.

### D-03: Sumber Kebenaran
Server adalah sumber kebenaran untuk waktu, skor, hak akses, token, dan status sesi.

### D-04: Alur Impor Soal
Template Word resmi → unggah → validasi → pratinjau → perbaikan → konfirmasi simpan.
Tidak ada impor langsung tanpa pratinjau.

### D-05: Token Ujian
- Rotasi otomatis setiap 5 menit.
- Rotasi manual oleh pihak berwenang.
- Rotasi tidak memutus sesi sah.
- Token kedaluwarsa tidak dapat memulai sesi baru.

### D-06: Deteksi Kecurangan
- Page Visibility API dengan toleransi 10 detik.
- Perpindahan halaman = indikator, bukan bukti.
- Sesi dapat dikunci sementara; hanya pengawas berwenang membuka kembali.
- Semua kejadian dicatat di audit log.

### D-07: Penyimpanan Jawaban
Autosave ke server. localStorage hanya sebagai penyangga sementara, bukan sumber kebenaran.

### D-08: Nilai
- Nilai objektif dihitung di server.
- Esai menunggu koreksi guru.
- Nilai final tidak ditetapkan jika esai wajib belum dinilai.
- Kebijakan pembulatan/bobot harus terdokumentasi.

### D-09: Hak Akses Laporan
- Siswa: hanya nilai milik sendiri, setelah dipublikasikan.
- Guru: hanya data yang menjadi kewenangannya.
- Pengawas: hanya data pelaksanaan yang diperlukan.
- Admin: sesuai kewenangan.

## Keputusan yang Belum Diputuskan (Perlu Konfirmasi)

### Q-01: Versi Laravel
Opsi: 11.x (LTS, PHP 8.2+), 12.x (terbaru).
Rekomendasi awal: **Laravel 11.x** karena stabilitas LTS dan kompatibilitas hosting.
Menunggu konfirmasi.

### Q-02: Versi PHP
Opsi: 8.2, 8.3.
Rekomendasi: **PHP 8.2** (kompatibel dengan Laravel 11 dan hosting umum).

### Q-03: Versi Node.js
Opsi: 20.x LTS, 22.x LTS.
Rekomendasi: **Node.js 20.x LTS**.

### Q-04: Strategi Real-time
Opsi:
- Supabase Realtime (terintegrasi dengan database).
- Laravel Reverb (WebSocket server bawaan Laravel 11).
- Pusher (berbayar).
Pertimbangan: target 1.200 siswa, latensi, biaya, kompleksitas.
Rekomendasi awal: **Supabase Realtime** untuk notifikasi pengawas dan status sesi,
karena sudah menggunakan Supabase. Perlu uji beban.

### Q-05: Strategi Queue
Opsi:
- Database queue (sederhana, cocok untuk awal).
- Redis queue (performa lebih baik, perlu Redis).
Pertimbangan: impor Word, pengiriman notifikasi, perhitungan nilai massal.
Rekomendasi awal: **database queue** untuk tahap awal, migrasi ke Redis jika uji beban membutuhkan.

### Q-06: Strategi Cache
Opsi:
- File cache (sederhana).
- Redis cache (performa lebih baik).
- Database cache.
Rekomendasi awal: **file cache** untuk tahap awal.

### Q-07: Struktur Nama Tabel
Opsi:
- Bahasa Indonesia (konsisten dengan prompt).
- Bahasa Inggris (konvensi Laravel umum).
Rekomendasi: **Bahasa Indonesia** sesuai prompt, dengan konvensi snake_case.
Contoh: `mata_pelajaran`, `bank_soal`, `jadwal_ujian`.

### Q-08: Struktur Nama Kolom
Opsi:
- Bahasa Indonesia (konsisten dengan tabel).
- Bahasa Inggris (konvensi Laravel).
Rekomendasi: **Bahasa Indonesia** untuk konsistensi.
Contoh: `nama_lengkap`, `tanggal_lahir`, `nilai_akhir`.

### Q-09: Hosting Final
Opsi:
- VPS (DigitalOcean, Vultr, IDCloudHost).
- Shared hosting (tidak disarankan untuk 1.200 siswa serentak).
- Platform as a Service (Forge, Vapor).
Rekomendasi: **VPS dengan minimal 4 vCPU, 8 GB RAM** untuk tahap awal.
Akan ditentukan setelah uji beban.

### Q-10: Domain
Belum ditentukan. Perlu konfirmasi dari sekolah.

### Q-11: Kebijakan Backup
Opsi:
- Backup database harian ke Supabase Storage.
- Backup mingguan ke penyimpanan eksternal.
- Retensi: 30 hari.
Rekomendasi awal: **backup harian otomatis, retensi 30 hari**.

### Q-12: Strategi Testing
Opsi:
- PHPUnit (standar Laravel).
- Pest (sintaks lebih ringkas).
- Laravel Dusk (browser testing).
Rekomendasi: **PHPUnit + Laravel Dusk** untuk cakupan penuh.

### Q-13: CI/CD Pipeline
Opsi:
- GitHub Actions.
- GitLab CI.
Rekomendasi: **GitHub Actions** (karena GitHub privat).

### Q-14: Monitoring dan Logging
Opsi:
- Laravel Telescope (development).
- Sentry (error tracking).
- Log tailing manual.
Rekomendasi awal: **Laravel Telescope** untuk development, **Sentry** untuk produksi.

## Keputusan tetap
D-15: Strategi Koneksi Database
- Menggunakan Supabase Transaction Pooler (Port 6543).
- Host: aws-0-ap-southeast-1.pooler.supabase.com
- Username format: postgres.{project_id}
- Alasan: Support IPv4 gratis (Direct Connection butuh add-on berbayar untuk IPv4), stabil, dan siap untuk skala 1.200 siswa serentak di production.