# DEPLOYMENT — Rencana Deployment

## Prinsip
- Development lokal terlebih dahulu.
- Hosting dipilih setelah pengujian lokal dan load test berhasil.
- HTTPS wajib di produksi.
- Backup dan pemulihan harus teruji.
- Deployment harus dapat diulang (repeatable).

## Lingkungan

### 1. Development (Lokal)
- **Server**: Laravel development server (`php artisan serve`).
- **Database**: Supabase PostgreSQL (development project).
- **Storage**: Supabase Storage (development bucket).
- **URL**: `http://localhost:8000`.
- **Debug**: Aktif.
- **Purpose**: Pengembangan dan pengujian fitur.

### 2. Staging
- **Server**: VPS atau shared hosting (TBD).
- **Database**: Supabase PostgreSQL (staging project).
- **Storage**: Supabase Storage (staging bucket).
- **URL**: `https://staging.sma12-pekanbaru.sch.id` (contoh).
- **Debug**: Nonaktif.
- **Purpose**: Pengujian akhir sebelum produksi, termasuk load test.

### 3. Production
- **Server**: VPS (minimal 4 vCPU, 8 GB RAM).
- **Database**: Supabase PostgreSQL (production project).
- **Storage**: Supabase Storage (production bucket).
- **URL**: `https://ujian.sma12-pekanbaru.sch.id` (contoh).
- **Debug**: Nonaktif.
- **Purpose**: Penggunaan nyata oleh siswa, guru, dan admin.

## Arsitektur Server (Produksi)

### Komponen
- **Web Server**: Nginx.
- **PHP**: PHP-FPM (PHP 8.2 atau 8.3).
- **Queue Worker**: Laravel Horizon (jika menggunakan Redis) atau database queue worker.
- **Cache**: File cache (tahap awal), Redis (jika dibutuhkan).
- **SSL**: Let's Encrypt (certbot).
- **Firewall**: UFW (Uncomplicated Firewall).
- **Fail2Ban**: Untuk mencegah brute force.

### Spesifikasi VPS (Rekomendasi Awal)
- **CPU**: 4 vCPU.
- **RAM**: 8 GB.
- **Storage**: 100 GB SSD.
- **Bandwidth**: Unlimited.
- **OS**: Ubuntu 22.04 LTS.
- **Provider**: DigitalOcean, Vultr, atau IDCloudHost (TBD).

### Topologi