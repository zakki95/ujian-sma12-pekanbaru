# Prosedur Pembuatan Admin & Reset Password

## Prinsip Keamanan
- Password tidak pernah di-hardcode atau disimpan dalam bentuk teks biasa.
- Password selalu di-hash menggunakan bcrypt (`Hash::make()`).
- Input password di terminal menggunakan mode tersembunyi (hidden input).

## Membuat Admin Pertama (Tanpa Membocorkan Rahasia)
Jalankan perintah berikut di terminal server/lokal:
```bash
php artisan app:create-admin