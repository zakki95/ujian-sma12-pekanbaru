# BLUEPRINT — Aplikasi Ujian Online SMA Negeri 12 Pekanbaru

## 1. Visi Produk
Menyediakan platform ujian online yang andal, aman, dan adil bagi 1.200 siswa
SMA Negeri 12 Pekanbaru secara serentak, dengan dukungan penuh bagi guru dan
admin sekolah.

## 2. Pengguna dan Peran
Tiga peran tetap:
1. **Admin** — mengelola jadwal, peserta, pengawas, publikasi hasil, laporan.
2. **Guru** — pembuat soal/guru mapel atau pengawas, berdasarkan penugasan.
3. **Siswa** — peserta ujian.

Tidak ada peran tambahan. Guru yang ditugaskan sebagai pengawas tetap berperan
sebagai "Guru" dengan hak akses tambahan berbasis penugasan.

## 3. Jenis Soal
- Pilihan ganda (single answer).
- Pilihan ganda kompleks (multiple answer).
- Benar/salah.
- Esai (dengan rubrik opsional).
- Soal dengan gambar (gambar disisipkan di template Word).

## 4. Alur Impor Soal
1. Guru mengunduh template `.docx` resmi.
2. Guru mengisi sesuai petunjuk.
3. Guru mengunggah berkas.
4. Sistem memeriksa keamanan dan struktur.
5. Sistem mengekstrak soal dan gambar.
6. Sistem memvalidasi dan menampilkan pratinjau.
7. Guru memperbaiki kesalahan yang dilaporkan.
8. Guru mengonfirmasi penyimpanan.

## 5. Mekanisme Ujian
- Timer divalidasi server.
- Jawaban disimpan via autosave ke server.
- Maksimal satu sesi aktif per peserta per jadwal.
- Token ujian berganti otomatis setiap 5 menit; rotasi manual tersedia.
- Rotasi token tidak memutus sesi yang sudah sah.
- Deteksi halaman tersembunyi via Page Visibility API (toleransi 10 detik).
- Pelanggaran berkelanjutan → sesi dikunci sementara, pengawas diberi notifikasi,
  hanya pengawas berwenang yang dapat membuka kembali.
- Semua kejadian dicatat di audit log.

## 6. Arsitektur Tingkat Tinggi
- **Backend**: Laravel (PHP).
- **Frontend**: Blade + Livewire + Tailwind CSS.
- **Database**: Supabase PostgreSQL.
- **Storage**: Supabase Storage (privat) untuk berkas.
- **Repo**: GitHub privat.
- **Real-time**: TBD (lihat DECISIONS.md).
- **Queue**: TBD.
- **Cache**: TBD.

## 7. Prinsip Desain
- Server adalah sumber kebenaran (waktu, skor, hak akses, token, status sesi).
- Browser tidak dipercaya.
- Semua operasi penting aman terhadap request ganda, retry, dan konkurensi.
- Jawaban tidak hanya disimpan di localStorage.
- Kunci jawaban tidak dikirim ke siswa sebelum diizinkan.
- Nilai objektif dihitung di server; esai menunggu koreksi.
- Authorization server-side pada setiap endpoint sensitif.

## 8. Target Kapasitas
1.200 siswa serentak. Kapasitas ini **harus dibuktikan dengan uji beban**,
bukan dijanjikan di muka.

## 9. Tahapan Pembangunan
Lihat `TASK_CHECKLIST.md`.