<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class MenuHelper
{
    /**
     * Menyusun grup menu sidebar TailAdmin.
     * Struktur disesuaikan dengan konsumsi nyata di layouts/sidebar.blade.php
     * Hanya menggunakan route yang sudah diverifikasi tersedia.
     */
    public static function getMenuGroups(): array
    {
        $peran = Auth::user()?->peran;
        $items = [];

        // 1. Dashboard Admin (hanya untuk admin)
        if ($peran === 'admin') {
            $items[] = ['name' => 'Dashboard Admin', 'icon' => 'user-circle', 'path' => '/admin/dashboard'];
        }

        // 2. Menu Akademik (Admin & Guru)
        if (in_array($peran, ['admin', 'guru'], true)) {
            $items[] = ['name' => 'Kelola Kelas', 'icon' => 'menu-two', 'path' => '/kelas'];
            $items[] = ['name' => 'Mata Pelajaran', 'icon' => 'globe', 'path' => '/mata-pelajaran'];
            // Menu Bank Soal (Admin & Guru)
            $items[] = ['name' => 'Bank Soal', 'icon' => 'info-circle', 'path' => '/bank-soal'];              // Menu Paket Soal (Admin & Guru)
              $items[] = ['name' => 'Paket Soal', 'icon' => 'info-circle', 'path' => '/paket-soal'];
        }

        // 3. Menu Khusus Admin
        if ($peran === 'admin') {
            $items[] = ['name' => 'Impor Data Siswa', 'icon' => 'gear', 'path' => '/import/siswa'];
        }

        return [
            [
                'title' => 'Menu Utama',
                'items' => array_values(array_filter($items)),
            ],
        ];
    }
}
