# STANDAR BAKU MIGRASI UI: TAILADMIN + LIVEWIRE
**Proyek:** Ujian Online SMA Negeri 12 Pekanbaru  
**Versi Stack:** Laravel 11, Livewire 4.4.8, Alpine.js 3.x, Tailwind CSS v4, Vite  
**Status:** Dokumen Resmi (Berlaku untuk seluruh migrasi halaman)

---

## 1. TUJUAN DAN CAKUPAN
Dokumen ini adalah **satu-satunya sumber kebenaran (Single Source of Truth)** untuk migrasi antarmuka (UI) ke TailAdmin. Tujuannya adalah menjamin konsistensi visual, stabilitas JavaScript (Alpine/Livewire), dan pencegahan regresi pada logika bisnis. Setiap migrasi halaman baru **wajib** mengikuti standar ini tanpa pengecualian.

---

## 2. PETA ARSITEKTUR
```text
[Browser Request]
      |
      +-> Blade View Biasa (e.g., dashboard.blade.php)
      |     +-> @extends('layouts.app')
      |
      +-> Livewire Full-Page Component (e.g., KelasManager)
            +-> #[Layout('layouts.livewire')]
                  +-> layouts/livewire.blade.php (Adapter)
                        +-> @extends('layouts.app')
                              +-> {{ $slot }} (Fragmen komponen Livewire)

[layouts.app.blade.php] (Layout Induk)
  + <head>: @vite, @livewireStyles, Inline Alpine.store('theme' & 'sidebar')
  + <body>: 
  |    + @include('layouts.sidebar')      (Sumber tunggal navigasi)
  |    + @include('layouts.app-header')    (Header, notifikasi, profil, toggle tema)
  |    + @yield('content')                 (Area konten utama, sudah memiliki padding)
  + </body>: @stack('scripts'), @livewireScripts