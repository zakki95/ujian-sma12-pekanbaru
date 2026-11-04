@extends('layouts.app')

@section('content')
    <!-- Header Halaman -->
    <div class="mb-6 text-gray-900 dark:text-white">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Admin</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Selamat datang, {{ auth()->user()->nama_lengkap }}</p>
    </div>

    <!-- Grid Statistik -->
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Pengguna -->
        <div class="rounded-2xl border border-gray-300 bg-white p-6 shadow-md transition-all duration-300 hover:shadow-xl hover:border-gray-400 dark:border-gray-600 dark:bg-white/[0.03] dark:hover:shadow-2xl dark:hover:border-gray-500">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-brand-50 dark:bg-brand-500/15">
                    <x-svg.user-circle class="h-6 w-6 text-brand-500" />
                </div>
                <div>
                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Total Pengguna</span>
                    <h3 class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Pengguna::count() }}</h3>
                </div>
            </div>
        </div>

        <!-- Total Kelas -->
        <div class="rounded-2xl border border-gray-300 bg-white p-6 shadow-md transition-all duration-300 hover:shadow-xl hover:border-gray-400 dark:border-gray-600 dark:bg-white/[0.03] dark:hover:shadow-2xl dark:hover:border-gray-500">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-green-50 dark:bg-green-500/15">
                    <x-svg.globe class="h-6 w-6 text-green-500" />
                </div>
                <div>
                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Total Kelas</span>
                    <h3 class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Kelas::count() }}</h3>
                </div>
            </div>
        </div>

        <!-- Mata Pelajaran -->
        <div class="rounded-2xl border border-gray-300 bg-white p-6 shadow-md transition-all duration-300 hover:shadow-xl hover:border-gray-400 dark:border-gray-600 dark:bg-white/[0.03] dark:hover:shadow-2xl dark:hover:border-gray-500">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-orange-50 dark:bg-orange-500/15">
                    <x-svg.info-circle class="h-6 w-6 text-orange-500" />
                </div>
                <div>
                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Mata Pelajaran</span>
                    <h3 class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\MataPelajaran::count() }}</h3>
                </div>
            </div>
        </div>

        <!-- Bank Soal -->
        <div class="rounded-2xl border border-gray-300 bg-white p-6 shadow-md transition-all duration-300 hover:shadow-xl hover:border-gray-400 dark:border-gray-600 dark:bg-white/[0.03] dark:hover:shadow-2xl dark:hover:border-gray-500">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-purple-50 dark:bg-purple-500/15">
                    <x-svg.gear class="h-6 w-6 text-purple-500" />
                </div>
                <div>
                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Bank Soal</span>
                    <h3 class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\BankSoal::count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Aksi Cepat -->
    <div class="mb-4">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Aksi Cepat</h2>
    </div>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <a href="/kelas" class="group block rounded-2xl border border-gray-300 bg-white p-6 shadow-md transition-all duration-300 hover:shadow-2xl hover:border-gray-400 dark:border-gray-600 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 transition-colors group-hover:text-brand-500 dark:text-white dark:group-hover:text-brand-400">Kelola Kelas</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Tambah, edit, atau hapus kelas</p>
                </div>
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gray-100 text-gray-500 transition-colors group-hover:bg-brand-50 group-hover:text-brand-500 dark:bg-gray-800 dark:text-gray-400 dark:group-hover:bg-brand-500/15 dark:group-hover:text-brand-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="/mata-pelajaran" class="group block rounded-2xl border border-gray-300 bg-white p-6 shadow-md transition-all duration-300 hover:shadow-2xl hover:border-gray-400 dark:border-gray-600 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 transition-colors group-hover:text-brand-500 dark:text-white dark:group-hover:text-brand-400">Kelola Mata Pelajaran</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Lihat mata pelajaran yang tersedia</p>
                </div>
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gray-100 text-gray-500 transition-colors group-hover:bg-brand-50 group-hover:text-brand-500 dark:bg-gray-800 dark:text-gray-400 dark:group-hover:bg-brand-500/15 dark:group-hover:text-brand-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="/import/siswa" class="group block rounded-2xl border border-gray-300 bg-white p-6 shadow-md transition-all duration-300 hover:shadow-2xl hover:border-gray-400 dark:border-gray-600 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 transition-colors group-hover:text-brand-500 dark:text-white dark:group-hover:text-brand-400">Impor Data Siswa</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Upload file Excel untuk impor massal</p>
                </div>
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gray-100 text-gray-500 transition-colors group-hover:bg-brand-50 group-hover:text-brand-500 dark:bg-gray-800 dark:text-gray-400 dark:group-hover:bg-brand-500/15 dark:group-hover:text-brand-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </div>
            </div>
        </a>
    </div>
@endsection
