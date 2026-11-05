@extends('layouts.app')

@section('content')
<div>
    <div class="mb-4">
        <a href="{{ url('mata-pelajaran') }}" class="text-sm text-brand-600 transition-colors hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300">
            ← Kembali ke Daftar Mata Pelajaran
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div class="bg-gradient-to-r from-brand-600 to-brand-700 px-6 py-4">
            <h1 class="text-2xl font-bold text-white">{{ $mata_pelajaran->nama_mapel }}</h1>
            <p class="text-brand-100 text-sm mt-1">Detail Mata Pelajaran</p>
        </div>
        <div class="px-6 py-4">
            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Mata Pelajaran</dt>
                    <dd class="mt-1 text-lg text-gray-900 dark:text-white">{{ $mata_pelajaran->nama_mapel }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kode Mata Pelajaran</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center rounded-lg bg-brand-50 px-3 py-1 text-sm font-medium text-brand-800 dark:bg-brand-900/20 dark:text-brand-400">
                            {{ $mata_pelajaran->kode_mapel }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="mt-6 rounded-lg border border-yellow-200 bg-yellow-50 p-4 dark:border-yellow-800 dark:bg-yellow-900/20">
        <div class="flex">
            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <div class="ml-3">
                <p class="text-sm text-yellow-700 dark:text-yellow-400">
                    <strong>Placeholder:</strong> Fitur detail mata pelajaran (daftar guru pengampu, daftar soal, statistik) akan diimplementasikan di tahap berikutnya.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection