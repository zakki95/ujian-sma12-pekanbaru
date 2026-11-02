<?php
return [
    'admin' => [
        ['name' => 'Dashboard', 'icon' => 'gear', 'path' => '/admin/dashboard'],
        ['name' => 'Kelas', 'icon' => 'database', 'path' => '/kelas'],
        ['name' => 'Mata Pelajaran', 'icon' => 'calendar', 'path' => '/mata-pelajaran'],
        ['name' => 'Impor Siswa', 'icon' => 'log-out', 'path' => '/import/siswa'],
    ],
    'guru' => [
        ['name' => 'Dashboard Guru', 'icon' => 'gear', 'path' => '/guru/dashboard'],
        ['name' => 'Mata Pelajaran Saya', 'icon' => 'calendar', 'path' => '/mata-pelajaran'],
    ],
    'siswa' => [
        ['name' => 'Dashboard Siswa', 'icon' => 'gear', 'path' => '/siswa/dashboard'],
    ],
    'guest' => [],
];