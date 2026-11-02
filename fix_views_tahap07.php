<?php
$base = __DIR__;

echo "=== 1. VIEW MATA PELAJARAN INDEX ===\n";
file_put_contents($base . '\resources\views\mapel\index.blade.php', <<<'BLADE'
<x-layout title="Mata Pelajaran">
    <div class="px-4 py-6 sm:px-0">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Mata Pelajaran</h1>
                <p class="mt-1 text-sm text-gray-600">Daftar mata pelajaran yang tersedia di sistem.</p>
            </div>
        </div>

        <div class="mt-6 bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mata Pelajaran</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($mapel as $m)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <a href="{{ url('mata-pelajaran/'.$m->id) }}" class="text-primary-600 hover:text-primary-900 hover:underline">
                                    {{ $m->nama_mapel }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                    {{ $m->kode_mapel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ url('mata-pelajaran/'.$m->id) }}" class="text-primary-600 hover:text-primary-900">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada mata pelajaran</h3>
                                <p class="mt-1 text-sm text-gray-500">Belum ada mata pelajaran yang ditambahkan ke sistem.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $mapel->links() }}
        </div>
    </div>
</x-layout>
BLADE);
echo "OK mapel/index.blade.php\n";

echo "=== 2. VIEW MATA PELAJARAN SHOW ===\n";
file_put_contents($base . '\resources\views\mapel\show.blade.php', <<<'BLADE'
<x-layout title="{{ $mata_pelajaran->nama_mapel }}">
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-4">
            <a href="{{ url('mata-pelajaran') }}" class="text-sm text-primary-600 hover:text-primary-900">
                ← Kembali ke Daftar Mata Pelajaran
            </a>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-6 py-4">
                <h1 class="text-2xl font-bold text-white">{{ $mata_pelajaran->nama_mapel }}</h1>
                <p class="text-primary-100 text-sm mt-1">Detail Mata Pelajaran</p>
            </div>
            <div class="px-6 py-4">
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nama Mata Pelajaran</dt>
                        <dd class="mt-1 text-lg text-gray-900">{{ $mata_pelajaran->nama_mapel }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Kode Mata Pelajaran</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary-100 text-primary-800">
                                {{ $mata_pelajaran->kode_mapel }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
            <div class="flex">
                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        <strong>Placeholder:</strong> Fitur detail mata pelajaran (daftar guru pengampu, daftar soal, statistik) akan diimplementasikan di tahap berikutnya.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layout>
BLADE);
echo "OK mapel/show.blade.php\n";

echo "=== 3. VIEW IMPORT SISWA FORM ===\n";
file_put_contents($base . '\resources\views\import\siswa-form.blade.php', <<<'BLADE'
<x-layout title="Impor Data Siswa">
    <div class="max-w-2xl mx-auto px-4 py-6 sm:px-0">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-6 py-4">
                <h1 class="text-2xl font-bold text-white">Impor Data Siswa</h1>
                <p class="text-primary-100 text-sm mt-1">Upload file Excel untuk menambahkan siswa secara massal</p>
            </div>

            <div class="px-6 py-6">
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 rounded">
                    <div class="flex">
                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                <strong>Format File:</strong> Excel (.xlsx, .xls) atau CSV dengan kolom:
                                <code class="bg-blue-100 px-1 rounded">no, nama_lengkap, email, nomor_induk</code>
                            </p>
                            <p class="text-sm text-blue-700 mt-1">
                                <strong>Format NIS:</strong> Harus diawali "NIS-" (contoh: NIS-12345)
                            </p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('import.siswa.preview') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-6">
                        <label for="file_siswa" class="block text-sm font-medium text-gray-700 mb-2">Pilih File</label>
                        <input type="file" id="file_siswa" name="file_siswa" accept=".xlsx,.xls,.csv" required
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                        @error('file_siswa')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end space-x-3">
                        <a href="/admin/dashboard" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Pratinjau Data →
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
BLADE);
echo "OK import/siswa-form.blade.php\n";

echo "=== 4. VIEW IMPORT SISWA PREVIEW ===\n";
file_put_contents($base . '\resources\views\import\siswa-preview.blade.php', <<<'BLADE'
<x-layout title="Pratinjau Impor Siswa">
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-4">
            <a href="{{ route('import.siswa.form') }}" class="text-sm text-primary-600 hover:text-primary-900">
                ← Kembali ke Form Impor
            </a>
        </div>

        <h1 class="text-2xl font-bold text-gray-900">Pratinjau Data Siswa</h1>
        <p class="mt-1 text-sm text-gray-600">Periksa data sebelum disimpan ke database.</p>

        @if(count($importErrors) > 0)
            <div class="mt-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
                <div class="flex">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Ditemukan {{ count($importErrors) }} baris bermasalah</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($importErrors as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <p class="mt-2 text-xs text-red-600">Baris bermasalah tidak akan disimpan. Perbaiki file Excel Anda dan upload ulang.</p>
                    </div>
                </div>
            </div>
        @endif

        @if(count($validRows) > 0)
            <div class="mt-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
                <div class="flex">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">{{ count($validRows) }} baris valid siap diimpor</h3>
                        <p class="mt-1 text-sm text-green-700">Password default untuk semua siswa: <code class="bg-green-100 px-1 rounded">password123</code></p>
                    </div>
                </div>
            </div>

            <div class="mt-6 bg-white shadow rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Lengkap</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIS</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($validRows as $r)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $r['no'] ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $r['nama_lengkap'] ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $r['email'] ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-primary-100 text-primary-800">
                                        {{ $r['nomor_induk'] ?? '-' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex items-center justify-end space-x-3">
                <a href="{{ route('import.siswa.form') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Batal
                </a>
                <form method="POST" action="{{ route('import.siswa.process') }}" class="inline">
                    @csrf
                    <input type="hidden" name="file_path" value="{{ $filePath }}">
                    <button type="submit" onclick="return confirm('Yakin ingin mengimpor {{ count($validRows) }} data siswa?')"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        ✓ Konfirmasi & Impor {{ count($validRows) }} Siswa
                    </button>
                </form>
            </div>
        @else
            <div class="mt-6 bg-white shadow rounded-lg p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data valid</h3>
                <p class="mt-1 text-sm text-gray-500">Semua baris memiliki error. Silakan perbaiki file dan upload ulang.</p>
                <div class="mt-6">
                    <a href="{{ route('import.siswa.form') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                        Upload Ulang
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-layout>
BLADE);
echo "OK import/siswa-preview.blade.php\n";

echo "\nSELESAI! Refresh browser untuk melihat perubahan.\n";