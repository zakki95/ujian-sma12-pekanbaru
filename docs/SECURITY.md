# SECURITY — Kebijakan Keamanan

## Prinsip
- Server adalah sumber kebenaran. Browser tidak dipercaya.
- Semua input divalidasi di server.
- Semua operasi sensitif memerlukan otorisasi.
- Rahasia tidak boleh bocor ke log, dokumen, atau Git.
- HTTPS wajib di produksi.
- Audit log untuk semua aksi penting.

## Autentikasi

### Password
- Hash dengan **bcrypt** (Laravel default).
- Minimal 8 karakter, kombinasi huruf besar, huruf kecil, angka, dan simbol.
- Password tidak boleh sama dengan 5 password terakhir (jika fitur change password diimplementasikan).

### Session
- Session driver: **database** (disimpan di Supabase PostgreSQL).
- Session lifetime: **120 menit** (dapat disesuaikan).
- Session di-rotate setelah login.
- Session di-destroy setelah logout.

### Two-Factor Authentication (2FA)
- Opsional untuk admin dan guru.
- Menggunakan **Laravel Fortify** atau **Jetstream**.
- Metode: TOTP (Google Authenticator, Authy).

### Rate Limiting
- Login: **5 percobaan per menit** per IP.
- Endpoint sensitif: **60 request per menit** per pengguna.
- API endpoint: **60 request per menit** per token.

## Otorisasi

### Peran
- **Admin**: Akses penuh ke semua fitur.
- **Guru**: Akses ke fitur yang menjadi kewenangannya (bank soal, paket soal, penilaian, laporan mapel).
- **Siswa**: Akses ke fitur ujian dan nilai pribadi.

### Policy
- Setiap model memiliki policy yang mendefinisikan hak akses.
- Policy divalidasi di server untuk setiap request.
- Tidak ada authorization di client-side saja.

### Middleware
- `role:admin,guru,siswa` untuk membatasi akses berdasarkan peran.
- `can:update,model` untuk membatasi akses berdasarkan policy.

## Validasi Input

### Server-Side Validation
- Semua input divalidasi di server menggunakan **Laravel Validation**.
- Validasi tipe data, panjang, format, dan rentang nilai.
- Validasi file: ekstensi, MIME type, ukuran.

### Sanitasi
- Output di-escape untuk mencegah XSS (Laravel Blade default).
- HTML yang diizinkan hanya dari sumber tepercaya (misalnya, editor WYSIWYG dengan konfigurasi ketat).

### SQL Injection
- Menggunakan **Eloquent ORM** atau **Query Builder** untuk semua query database.
- Tidak ada raw query kecuali benar-benar diperlukan dan divalidasi dengan ketat.

### CSRF Protection
- Laravel CSRF protection aktif untuk semua form POST, PUT, DELETE.
- Token CSRF divalidasi di server.

## Data Sensitif

### Kunci Jawaban
- Tidak dikirim ke siswa sebelum diizinkan.
- Disimpan di database dengan enkripsi (opsional).
- Hanya dapat diakses oleh guru dan admin.

### Nilai
- Dihitung di server.
- Tidak dapat diubah oleh siswa.
- Perubahan nilai oleh guru dicatat di audit log.

### Token Ujian
- Digenerate di server.
- Rotasi otomatis setiap 5 menit.
- Token kedaluwarsa tidak dapat digunakan untuk memulai sesi baru.

### File Upload
- Validasi ekstensi, MIME type, dan ukuran.
- File disimpan di **Supabase Storage** (privat).
- File tidak dieksekusi di server.
- Gambar diekstrak dari DOCX dengan validasi ketat.

## Audit Log

### Aksi yang Dicatat
- Login dan logout.
- Perubahan data penting (soal, nilai, jadwal).
- Akses ke data sensitif (kunci jawaban, nilai).
- Pelanggaran (halaman tersembunyi, dll).
- Rotasi token.

### Struktur Audit Log
- `pengguna_id`: Siapa yang melakukan aksi.
- `aksi`: Apa yang dilakukan.
- `model_type` dan `model_id`: Objek yang diubah.
- `data_lama` dan `data_baru`: Perubahan data.
- `ip_address` dan `user_agent`: Informasi request.
- `created_at`: Waktu aksi.

### Retensi
- Audit log disimpan minimal **1 tahun**.
- Dapat diekspor untuk keperluan audit.

## HTTPS

### Produksi
- HTTPS wajib.
- HSTS (HTTP Strict Transport Security) aktif.
- Certificate dari Let's Encrypt atau penyedia berbayar.

### Development
- HTTP diperbolehkan untuk kemudahan development.
- Tidak ada data produksi di development.

## Backup dan Pemulihan

### Database
- Backup harian otomatis ke Supabase Storage.
- Retensi: **30 hari**.
- Backup diuji secara periodik (restore test).

### File
- File di Supabase Storage sudah memiliki versioning (jika diaktifkan).
- Backup tambahan ke penyimpanan eksternal (opsional).

### Pemulihan
- Prosedur pemulihan didokumentasikan di DEPLOYMENT.md.
- RTO (Recovery Time Objective): **< 4 jam**.
- RPO (Recovery Point Objective): **< 24 jam**.

## Monitoring dan Logging

### Error Tracking
- **Sentry** untuk error tracking di produksi.
- **Laravel Telescope** untuk debugging di development.

### Application Logging
- Log level: **error** untuk produksi, **debug** untuk development.
- Log disimpan di file atau dikirim ke layanan logging (opsional).
- Log tidak mengandung data sensitif (password, token, dll).

### Monitoring
- Uptime monitoring (opsional, misalnya UptimeRobot).
- Performance monitoring (opsional, misalnya New Relic).

## Keamanan DOCX Parser

### Validasi
- Ekstensi file: `.docx` saja.
- MIME type: `application/vnd.openxmlformats-officedocument.wordprocessingml.document`.
- Ukuran maksimal: **10 MB**.
- Struktur arsip DOCX divalidasi (ZIP valid, file XML valid).

### Ekstraksi
- Gambar diekstrak dengan validasi tipe dan ukuran.
- Teks diekstrak dengan validasi struktur (tag XML yang diharapkan).
- Makro dan konten tertanam **tidak dieksekusi**.

### Sanitasi
- HTML yang diekstrak dari DOCX dibersihkan (strip tag berbahaya).
- Gambar yang diekstrak divalidasi (tipe, ukuran, tidak ada eksekusi).

## Pertanyaan Terbuka
1. Apakah perlu enkripsi tambahan untuk kunci jawaban di database?
2. Apakah perlu integrasi dengan layanan WAF (Web Application Firewall)?
3. Apakah perlu penetration testing oleh pihak ketiga sebelum peluncuran?