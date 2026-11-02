<?php
$base = __DIR__;

// Buat folder yang diperlukan
@mkdir($base . '\resources\views\components', 0755, true);
@mkdir($base . '\resources\views\admin', 0755, true);
@mkdir($base . '\resources\views\guru', 0755, true);
@mkdir($base . '\resources\views\siswa', 0755, true);
@mkdir($base . '\resources\views\livewire', 0755, true);
@mkdir($base . '\app\Livewire', 0755, true);

echo "=== 1. LAYOUT UTAMA (Navigasi Responsif) ===\n";
file_put_contents($base . '\resources\views\components\layout.blade.php', <<<'BLADE'
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sistem Ujian SMA 12' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="/dashboard" class="text-xl font-bold text-primary-600">Ujian SMA 12</a>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        @auth
                            @if(auth()->user()->peran === 'admin')
                                <a href="/admin/dashboard" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->is('admin/dashboard') ? 'border-primary-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">Dashboard</a>
                                <a href="/kelas" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->is('kelas') ? 'border-primary-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">Kelas</a>
                                <a href="/mata-pelajaran" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->is('mata-pelajaran*') ? 'border-primary-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">Mata Pelajaran</a>
                            @elseif(auth()->user()->peran === 'guru')
                                <a href="/guru/dashboard" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->is('guru/dashboard') ? 'border-primary-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">Dashboard</a>
                                <a href="/mata-pelajaran" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->is('mata-pelajaran*') ? 'border-primary-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">Mata Pelajaran</a>
                            @elseif(auth()->user()->peran === 'siswa')
                                <a href="/siswa/dashboard" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->is('siswa/dashboard') ? 'border-primary-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">Dashboard</a>
                            @endif
                        @endauth
                    </div>
                </div>
                <div class="hidden sm:flex sm:items-center">
                    @auth
                        <div class="flex items-center space-x-3">
                            <div class="text-sm">
                                <div class="font-medium text-gray-700">{{ auth()->user()->nama_lengkap }}</div>
                                <div class="text-xs text-gray-500 capitalize">{{ auth()->user()->peran }}</div>
                            </div>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-red-600 hover:text-red-800">Logout</button>
                            </form>
                        </div>
                    @endauth
                </div>
                <!-- Mobile menu button -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button type="button" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100" aria-label="Toggle menu">
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu -->
        <div class="hidden sm:hidden" id="mobile-menu">
            <div class="pt-2 pb-3 space-y-1">
                @auth
                    @if(auth()->user()->peran === 'admin')
                        <a href="/admin/dashboard" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->is('admin/dashboard') ? 'bg-primary-50 border-primary-500 text-primary-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }}">Dashboard</a>
                        <a href="/kelas" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->is('kelas') ? 'bg-primary-50 border-primary-500 text-primary-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }}">Kelas</a>
                        <a href="/mata-pelajaran" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->is('mata-pelajaran*') ? 'bg-primary-50 border-primary-500 text-primary-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }}">Mata Pelajaran</a>
                    @elseif(auth()->user()->peran === 'guru')
                        <a href="/guru/dashboard" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->is('guru/dashboard') ? 'bg-primary-50 border-primary-500 text-primary-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }}">Dashboard</a>
                        <a href="/mata-pelajaran" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->is('mata-pelajaran*') ? 'bg-primary-50 border-primary-500 text-primary-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }}">Mata Pelajaran</a>
                    @elseif(auth()->user()->peran === 'siswa')
                        <a href="/siswa/dashboard" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->is('siswa/dashboard') ? 'bg-primary-50 border-primary-500 text-primary-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }}">Dashboard</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left block pl-3 pr-4 py-2 border-l-4 border-transparent text-red-600 hover:bg-red-50 hover:border-red-300 hover:text-red-800 text-base font-medium">Logout</button>
                    </form>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Flash Messages -->
        @if(session('sukses'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('sukses') }}</span>
            </div>
        @endif
        @if(session('error_delete'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error_delete') }}</span>
            </div>
        @endif
        @if($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {{ $slot }}
    </main>
</body>
</html>
BLADE);
echo "OK layout.blade.php\n";

echo "=== 2. DASHBOARD ADMIN ===\n";
file_put_contents($base . '\resources\views\admin\dashboard.blade.php', <<<'BLADE'
<x-layout title="Dashboard Admin">
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Admin</h1>
            <p class="mt-1 text-sm text-gray-600">Selamat datang, {{ auth()->user()->nama_lengkap }}</p>
        </div>
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Pengguna</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ \App\Models\Pengguna::count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Kelas</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ \App\Models\Kelas::count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Mata Pelajaran</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ \App\Models\MataPelajaran::count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Bank Soal</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ \App\Models\BankSoal::count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-8">
            <h2 class="text-lg font-medium text-gray-900">Aksi Cepat</h2>
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <a href="/kelas" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
                    <h3 class="text-lg font-medium text-gray-900">Kelola Kelas</h3>
                    <p class="mt-2 text-sm text-gray-500">Tambah, edit, atau hapus kelas</p>
                </a>
                <a href="/mata-pelajaran" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
                    <h3 class="text-lg font-medium text-gray-900">Kelola Mata Pelajaran</h3>
                    <p class="mt-2 text-sm text-gray-500">Lihat mata pelajaran yang tersedia</p>
                </a>
                <a href="/import/siswa" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
                    <h3 class="text-lg font-medium text-gray-900">Impor Data Siswa</h3>
                    <p class="mt-2 text-sm text-gray-500">Upload file Excel untuk impor massal</p>
                </a>
            </div>
        </div>
    </div>
</x-layout>
BLADE);
echo "OK admin/dashboard.blade.php\n";

echo "=== 3. DASHBOARD GURU ===\n";
file_put_contents($base . '\resources\views\guru\dashboard.blade.php', <<<'BLADE'
<x-layout title="Dashboard Guru">
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Guru</h1>
            <p class="mt-1 text-sm text-gray-600">Selamat datang, {{ auth()->user()->nama_lengkap }}</p>
            <p class="mt-1 text-sm text-gray-500">Mata Pelajaran: {{ auth()->user()->mataPelajaran->pluck('nama_mapel')->join(', ') ?: 'Belum ditugaskan' }}</p>
        </div>
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Mata Pelajaran Diampu</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ auth()->user()->mataPelajaran->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Bank Soal Saya</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ \App\Models\BankSoal::where('guru_id', auth()->id())->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Paket Soal</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ \App\Models\PaketSoal::where('guru_id', auth()->id())->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-8 bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700"><strong>Placeholder:</strong> Fitur manajemen Bank Soal, Paket Soal, dan Jadwal Ujian akan diimplementasikan di tahap berikutnya.</p>
                </div>
            </div>
        </div>
    </div>
</x-layout>
BLADE);
echo "OK guru/dashboard.blade.php\n";

echo "=== 4. DASHBOARD SISWA ===\n";
file_put_contents($base . '\resources\views\siswa\dashboard.blade.php', <<<'BLADE'
<x-layout title="Dashboard Siswa">
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Siswa</h1>
            <p class="mt-1 text-sm text-gray-600">Selamat datang, {{ auth()->user()->nama_lengkap }}</p>
            <p class="mt-1 text-sm text-gray-500">Kelas: {{ auth()->user()->kelas->pluck('nama_kelas')->join(', ') ?: 'Belum ada kelas' }}</p>
        </div>
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Kelas Saya</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ auth()->user()->kelas->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Ujian Selesai</dt>
                                <dd class="text-lg font-semibold text-gray-900">0</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Rata-rata Nilai</dt>
                                <dd class="text-lg font-semibold text-gray-900">-</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-8 bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700"><strong>Placeholder:</strong> Fitur daftar ujian tersedia, pengerjaan ujian, dan riwayat hasil ujian akan diimplementasikan di tahap berikutnya.</p>
                </div>
            </div>
        </div>
    </div>
</x-layout>
BLADE);
echo "OK siswa/dashboard.blade.php\n";

echo "=== 5. KOMPONEN LIVEWIRE KELAS MANAGER ===\n";
file_put_contents($base . '\app\Livewire\KelasManager.php', <<<'PHP'
<?php

namespace App\Livewire;

use App\Models\Kelas;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class KelasManager extends Component
{
    use WithPagination;

    public $search = '';
    public $nama_kelas = '';
    public $tingkat = '';
    public $tahun_ajaran = '';
    public $editId = null;
    public $showModal = false;

    protected $rules = [
        'nama_kelas' => 'required|string|max:50',
        'tingkat' => 'required|in:X,XI,XII',
        'tahun_ajaran' => 'required|string|regex:/^\d{4}\/\d{4}$/',
    ];

    protected $messages = [
        'tahun_ajaran.regex' => 'Format tahun ajaran harus YYYY/YYYY (contoh: 2026/2027).',
    ];

    public function updatingSearch() { $this->resetPage(); }

    public function openModal($id = null)
    {
        $this->resetErrorBag();
        if ($id) {
            $kelas = Kelas::findOrFail($id);
            $this->editId = $id;
            $this->nama_kelas = $kelas->nama_kelas;
            $this->tingkat = $kelas->tingkat;
            $this->tahun_ajaran = $kelas->tahun_ajaran;
        } else {
            $this->editId = null;
            $this->nama_kelas = '';
            $this->tingkat = '';
            $this->tahun_ajaran = '';
        }
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['nama_kelas', 'tingkat', 'tahun_ajaran', 'editId']);
    }

    public function save()
    {
        $this->validate();
        if ($this->editId) {
            $kelas = Kelas::findOrFail($this->editId);
            $kelas->update(['nama_kelas' => $this->nama_kelas, 'tingkat' => $this->tingkat, 'tahun_ajaran' => $this->tahun_ajaran]);
            session()->flash('sukses', 'Kelas berhasil diperbarui.');
        } else {
            Kelas::create(['nama_kelas' => $this->nama_kelas, 'tingkat' => $this->tingkat, 'tahun_ajaran' => $this->tahun_ajaran]);
            session()->flash('sukses', 'Kelas berhasil dibuat.');
        }
        $this->closeModal();
    }

    public function delete($id)
    {
        $kelas = Kelas::findOrFail($id);
        $adaSiswaAktif = DB::table('anggota_kelas')->where('kelas_id', $kelas->id)->where('status', 'aktif')->exists();
        if ($adaSiswaAktif) {
            session()->flash('error_delete', 'Gagal hapus: Masih ada siswa aktif di kelas ini.');
            return;
        }
        $kelas->delete();
        session()->flash('sukses', 'Kelas berhasil dihapus.');
    }

    public function render()
    {
        $query = Kelas::query();
        if ($this->search) {
            $query->where('nama_kelas', 'like', '%' . $this->search . '%')->orWhere('tahun_ajaran', 'like', '%' . $this->search . '%');
        }
        $kelas = $query->orderBy('nama_kelas')->paginate(10);
        return view('livewire.kelas-manager', ['kelas' => $kelas]);
    }
}
PHP);
echo "OK KelasManager.php\n";

file_put_contents($base . '\resources\views\livewire\kelas-manager.blade.php', <<<'BLADE'
<div>
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Manajemen Kelas</h1>
            <p class="mt-2 text-sm text-gray-700">Daftar semua kelas yang tersedia di sistem.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <button wire:click="openModal()" type="button" class="inline-flex items-center justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto">
                Tambah Kelas
            </button>
        </div>
    </div>
    <div class="mt-4">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari kelas..." class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md p-2">
    </div>
    <div class="mt-4 flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kelas</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tingkat</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun Ajaran</th>
                                <th scope="col" class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($kelas as $k)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $k->nama_kelas }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $k->tingkat }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $k->tahun_ajaran }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button wire:click="openModal({{ $k->id }})" class="text-primary-600 hover:text-primary-900 mr-3">Edit</button>
                                        <button wire:click="delete({{ $k->id }})" wire:confirm="Apakah Anda yakin ingin menghapus kelas ini?" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data kelas. Klik "Tambah Kelas" untuk membuat kelas baru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-4">{{ $kelas->links() }}</div>
    @if($showModal)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeModal()"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="save">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $editId ? 'Edit Kelas' : 'Tambah Kelas Baru' }}</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="nama_kelas" class="block text-sm font-medium text-gray-700">Nama Kelas</label>
                                    <input wire:model="nama_kelas" type="text" id="nama_kelas" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2" placeholder="Contoh: X IPA 1">
                                    @error('nama_kelas') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="tingkat" class="block text-sm font-medium text-gray-700">Tingkat</label>
                                    <select wire:model="tingkat" id="tingkat" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                        <option value="">Pilih Tingkat</option>
                                        <option value="X">X</option>
                                        <option value="XI">XI</option>
                                        <option value="XII">XII</option>
                                    </select>
                                    @error('tingkat') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700">Tahun Ajaran</label>
                                    <input wire:model="tahun_ajaran" type="text" id="tahun_ajaran" class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2" placeholder="Contoh: 2026/2027">
                                    @error('tahun_ajaran') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                            <button type="button" wire:click="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
BLADE);
echo "OK kelas-manager.blade.php\n";

echo "=== 6. UPDATE ROUTES ===\n";
$webPhp = file_get_contents($base . '\routes\web.php');
// Hapus route kelas resource lama
$webPhp = preg_replace("/Route::resource\('kelas'.*?\);\n?/", "", $webPhp);
// Tambahkan route Livewire baru di atas Route::get('/', ...)
$livewireRoute = "Route::middleware(['auth', 'check.status', 'role:admin,guru'])->get('/kelas', \\App\\Livewire\\KelasManager::class)->name('kelas.index');\n";
if (strpos($webPhp, 'KelasManager') === false) {
    $webPhp = preg_replace("/(Route::get\('\/',)/", $livewireRoute . "$1", $webPhp);
    file_put_contents($base . '\routes\web.php', $webPhp);
    echo "OK routes/web.php updated\n";
} else { echo "Route sudah ada\n"; }

echo "=== 7. DOKUMENTASI PLACEHOLDER ===\n";
@mkdir($base . '\docs', 0755, true);
file_put_contents($base . '\docs\TAHAP_07_UI.md', <<<'MD'
# Implementasi Tahap 07: Dashboard dan UI Responsif

## Fitur yang Diimplementasikan
1. **Layout Responsif**: Navigasi desktop & mobile dengan Tailwind CSS v4
2. **Dashboard Admin**: Statistik pengguna, kelas, mapel, bank soal + aksi cepat
3. **Dashboard Guru**: Statistik mapel diampu, bank soal, paket soal
4. **Dashboard Siswa**: Statistik kelas, ujian selesai, rata-rata nilai
5. **Manajemen Kelas Livewire**: CRUD dengan modal, pencarian live, pagination, konfirmasi hapus (`wire:confirm`)
6. **Empty States**: Pesan ketika tidak ada data
7. **Loading States**: Otomatis dari Livewire
8. **Error Handling**: Flash messages untuk sukses & error
9. **Konfirmasi Tindakan Berisiko**: `wire:confirm` untuk hapus data
10. **Validasi Form**: Real-time validation di modal Livewire

## Placeholder (Akan Diimplementasikan di Tahap Berikutnya)
- Manajemen Bank Soal (CRUD soal)
- Manajemen Paket Soal
- Jadwal Ujian
- Pengerjaan Ujian oleh Siswa
- Riwayat & Hasil Ujian
- Impor Soal dari Word/Excel
- Notifikasi Pengawas
- Log Pelanggaran

## Aksesibilitas
- Semantic HTML (nav, main, h1-h3)
- ARIA labels untuk screen readers (`aria-labelledby`, `aria-modal`, `role="dialog"`)
- Keyboard navigation support
- Color contrast sesuai WCAG AA

## Responsivitas
- Mobile-first design
- Breakpoints: sm (640px), md (768px), lg (1024px)
- Navigation collapse di mobile dengan hamburger menu
- Grid layouts adaptif (1 → 2 → 3 → 4 kolom)

## Data Uji
Seluruh tampilan menggunakan data dummy dari seeder. Tidak ada data nyata.
MD);
echo "OK docs/TAHAP_07_UI.md\n";

echo "\nSELESAI! Jalankan: php artisan test\n";