# TASK CHECKLIST — Daftar Tugas per Tahap

## Tahap 00: Analisis dan Dokumentasi Awal
- [x] Memeriksa kondisi awal proyek.
- [x] Membuat BLUEPRINT.md.
- [x] Membuat PROJECT_STATE.md.
- [x] Membuat DECISIONS.md.
- [x] Membuat DATABASE_SCHEMA.md (draft awal).
- [x] Membuat TASK_CHECKLIST.md.
- [x] Membuat TESTING.md.
- [x] Membuat SECURITY.md.
- [x] Membuat DEPLOYMENT.md.
- [x] Membuat HANDOFF.md.
- [x] Membuat README.md.
- [x] Mengidentifikasi risiko dan pertanyaan terbuka.
- [ ] **Konfirmasi pemilik proyek atas keputusan yang belum diputuskan.**

**Kriteria Penerimaan**:
- Semua dokumen wajib tersedia dan konsisten.
- Keputusan yang sudah tetap tercatat.
- Keputusan yang belum ditetapkan dicatat sebagai pertanyaan terbuka.
- Risiko utama diidentifikasi.
- Rancangan skema database awal didokumentasikan.
- Tidak ada kode aplikasi yang ditulis.
- Tidak ada dependensi yang dipasang.
- Tidak ada rahasia yang bocor ke dokumen.

---

## Tahap 01: Audit Lingkungan Lokal
- [ ] Memeriksa versi PHP yang terinstal.
- [ ] Memeriksa versi Composer yang terinstal.
- [ ] Memeriksa versi Node.js dan npm yang terinstal.
- [ ] Memeriksa ekstensi PHP yang diperlukan.
- [ ] Memeriksa koneksi internet.
- [ ] Memeriksa ruang disk yang tersedia.
- [ ] Mendokumentasikan hasil audit.

**Kriteria Penerimaan**:
- Semua komponen lingkungan terverifikasi.
- Hasil audit didokumentasikan di PROJECT_STATE.md.
- Tidak ada instalasi atau konfigurasi yang dilakukan.

---

## Tahap 02: Laravel dan GitHub
- [ ] Membuat repositori GitHub privat.
- [ ] Menginisialisasi repositori Git lokal.
- [ ] Memasang Laravel 11.x (atau versi yang disepakati).
- [ ] Mengonfigurasi `.gitignore`.
- [ ] Membuat commit awal.
- [ ] Push ke GitHub.

**Kriteria Penerimaan**:
- Repositori GitHub tersedia dan privat.
- Laravel terinstal dan dapat dijalankan dengan `php artisan serve`.
- Commit awal tersedia di GitHub.
- Tidak ada rahasia yang ter-commit.

---

## Tahap 03: Supabase PostgreSQL
- [ ] Membuat proyek Supabase.
- [ ] Mendapatkan kredensial database.
- [ ] Mengonfigurasi `.env` Laravel.
- [ ] Menguji koneksi database.
- [ ] Mendokumentasikan konfigurasi (tanpa rahasia).

**Kriteria Penerimaan**:
- Koneksi database berhasil.
- Konfigurasi didokumentasikan di DEPLOYMENT.md.
- Tidak ada rahasia yang bocor ke dokumen atau Git.

---

## Tahap 04: Database, Model, Relasi, Migrasi, Indeks
- [ ] Membuat migrasi untuk semua tabel sesuai DATABASE_SCHEMA.md.
- [ ] Membuat model Eloquent untuk setiap tabel.
- [ ] Mendefinisikan relasi antar model.
- [ ] Menjalankan migrasi.
- [ ] Menguji relasi dengan seeder.
- [ ] Memverifikasi indeks dan constraint.

**Kriteria Penerimaan**:
- Semua tabel terbuat dengan struktur yang sesuai.
- Semua model dan relasi berfungsi.
- Tidak ada error saat migrasi.
- Seeder berhasil mengisi data uji.

---

## Tahap 05: Autentikasi dan Hak Akses
- [ ] Memasang Laravel Breeze atau Jetstream (TBD).
- [ ] Mengonfigurasi autentikasi untuk tiga peran.
- [ ] Membuat middleware untuk otorisasi berbasis peran.
- [ ] Membuat policy untuk setiap model.
- [ ] Menguji login, logout, dan hak akses.

**Kriteria Penerimaan**:
- Pengguna dapat login sesuai peran.
- Middleware dan policy membatasi akses dengan benar.
- Tidak ada bypass hak akses.

---

(Tahap 06-18 akan dirinci lebih lanjut setelah tahap sebelumnya selesai)

---

## Catatan
- Setiap tahap hanya dimulai setelah tahap sebelumnya LULUS.
- Kriteria penerimaan harus diverifikasi dengan pengujian aktual.
- Dokumentasi diperbarui setelah setiap tahap.