@extends('layouts.app')

@section('content')
<div>
    <div class="mb-4">
        <a href="{{ route('import.siswa.form') }}" class="text-sm text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300">
            ← Kembali ke Form Impor
        </a>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pratinjau Data Siswa</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Periksa data sebelum disimpan ke database.</p>
    </div>

    @if(count($importErrors) > 0)
        <div class="mt-6 bg-red-50 border-l-4 border-red-400 p-4 rounded dark:border-red-800 dark:bg-red-900/20">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-400">Ditemukan {{ count($importErrors) }} baris bermasalah</h3>
                    <div class="mt-2 text-sm text-red-700 dark:text-red-400">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($importErrors as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <p class="mt-2 text-xs text-red-600 dark:text-red-400">Baris bermasalah tidak akan disimpan. Perbaiki file Excel Anda dan upload ulang.</p>
                </div>
            </div>
        </div>
    @endif

    @if(count($validRows) > 0)
        <div class="mt-6 bg-green-50 border-l-4 border-green-400 p-4 rounded dark:border-green-800 dark:bg-green-900/20">
            <div class="flex">
                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800 dark:text-green-400">{{ count($validRows) }} baris valid siap diimpor</h3>
                    <p class="mt-1 text-sm text-green-700 dark:text-green-400">Password default untuk semua siswa: <code class="bg-green-100 dark:bg-green-900/40 px-1 rounded">password123</code></p>
                </div>
            </div>
        </div>

        <div class="mt-6 rounded-xl border border-gray-300 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-400">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-400">Nama Lengkap</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-400">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-400">NIS</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-300 dark:divide-gray-700">
                    @foreach($validRows as $r)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $r['no'] ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $r['nama_lengkap'] ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $r['email'] ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                <span class="inline-flex items-center rounded-lg bg-brand-50 px-2.5 py-0.5 text-xs font-medium text-brand-800 dark:bg-brand-900/20 dark:text-brand-400">
                                    {{ $r['nomor_induk'] ?? '-' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex items-center justify-end space-x-3">
            <a href="{{ route('import.siswa.form') }}" class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                Batal
            </a>
            <form method="POST" action="{{ route('import.siswa.process') }}" class="inline">
                @csrf
                <input type="hidden" name="file_path" value="{{ $filePath }}">
                <button type="submit" onclick="return confirm('Yakin ingin mengimpor {{ count($validRows) }} data siswa?')"
                    class="px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-900">
                    ✓ Konfirmasi & Impor {{ count($validRows) }} Siswa
                </button>
            </form>
        </div>
    @else
        <div class="mt-6 rounded-xl border border-gray-300 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900 p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada data valid</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Semua baris memiliki error. Silakan perbaiki file dan upload ulang.</p>
            <div class="mt-6">
                <a href="{{ route('import.siswa.form') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-brand-600 hover:bg-brand-700">
                    Upload Ulang
                </a>
            </div>
        </div>
    @endif
</div>
@endsection