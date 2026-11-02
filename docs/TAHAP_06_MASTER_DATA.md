# Implementasi Tahap 06: Master Data Akademik

## Fitur yang Diimplementasikan
1. **CRUD & Validasi Ketat**: Kelas dan Mata Pelajaran menggunakan Form Request (regex format tahun ajaran `YYYY/YYYY`, validasi unik).
2. **Otorisasi Server-Side (Policies)**: 
   - Guru hanya dapat melihat `MataPelajaran` yang ditugaskan kepadanya.
   - Hanya Admin yang dapat Membuat/Menghapus `Kelas`.
3. **Keamanan Penghapusan Terkait**: Penghapusan `Kelas` diblokir (dengan pesan flash error) jika masih memiliki relasi `AnggotaKelas` dengan status 'aktif' (dicek via Query Builder untuk akurasi mutlak).
4. **Impor Excel dengan Pratinjau (Preview)**: Menggunakan `maatwebsite/excel` dengan validasi kolom, duplikasi, dan format identitas (NIS-xxxxx) sebelum disimpan.
5. **UI/UX**: Pencarian (search) dan Paginasi (10 item/halaman).

## Data Uji
Seluruh pengujian menggunakan **Data Dummy** yang dihasilkan oleh Laravel Factory. Tidak ada data siswa atau institusi nyata yang digunakan.