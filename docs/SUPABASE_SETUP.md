# Panduan Konfigurasi Supabase PostgreSQL

## 1. Buat Akun Supabase (Jika Belum Ada)

1. Buka: https://supabase.com
2. Klik **"Start your project"** atau **"Sign In"**
3. Login dengan GitHub (direkomendasikan) atau email
4. **Gratis untuk development**: 500MB database, 2GB bandwidth, cukup untuk testing

## 2. Buat Proyek Supabase Baru

1. Setelah login, klik **"New Project"**
2. Isi form:
   - **Name**: `ujian-sma12-pekanbaru`
   - **Database Password**: Buat password yang kuat (simpan di password manager!)
   - **Region**: **Southeast Asia (Singapore)** — latensi terendah ke Indonesia
   - **Pricing Plan**: **Free** (bisa upgrade nanti)
3. Klik **"Create new project"**
4. **Tunggu 2-5 menit** — Supabase sedang membuat database Anda

## 3. Dapatkan Kredensial Database

Setelah proyek selesai dibuat:

### Opsi A: Connection String (Direkomendasikan)
1. Di dashboard Supabase, klik **"Settings"** (ikon gear di sidebar kiri)
2. Klik **"Database"**
3. Scroll ke bagian **"Connection string"**
4. Pilih tab **"URI"**
5. Klik **"Copy"** — Anda akan mendapat string seperti:


# Panduan Konfigurasi Supabase PostgreSQL

## Status Koneksi Aktual
- **Metode**: Transaction Pooler
- **Host**: `aws-0-ap-southeast-1.pooler.supabase.com`
- **Port**: `6543`
- **Database**: `postgres`
- **Username**: `postgres.{project_id}`

## Cara Mendapatkan Kredensial
1. Buka Dashboard Supabase -> Klik **Connect** di header.
2. Pilih **Transaction pooler**.
3. Salin Host, Port, dan User dari bagian *Connection parameters*.
4. Untuk password, gunakan password database yang dibuat saat inisialisasi proyek (atau reset via Settings -> Database).

## Konfigurasi `.env` Lokal
```env
DB_CONNECTION=pgsql
DB_HOST=aws-0-ap-southeast-1.pooler.supabase.com
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres.{project_id}
DB_PASSWORD={password_anda}