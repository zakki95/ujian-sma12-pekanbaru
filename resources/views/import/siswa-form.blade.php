@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="rounded-xl border border-gray-300 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900 overflow-hidden">
        
        <!-- HEADER: Gradient Brand, Padding Proporsional -->
        <div class="bg-gradient-to-r from-brand-600 to-brand-700 px-5 py-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/10 text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-white">Impor Data Siswa</h1>
                    <p class="text-brand-100 text-xs mt-0.5">Tambahkan data siswa secara massal melalui file Excel atau CSV.</p>
                </div>
            </div>
        </div>

        <div class="p-5 space-y-5">
            
            <!-- INFO BOX: Spacing lebih rapat -->
            <div class="rounded-lg border-l-4 border-blue-400 bg-blue-50 p-3 dark:border-blue-800 dark:bg-blue-900/20">
                <div class="flex gap-3">
                    <svg class="h-5 w-5 flex-shrink-0 text-blue-500 dark:text-blue-400 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    <div class="space-y-1">
                        <p class="text-sm font-semibold text-blue-800 dark:text-blue-300">Format File yang Didukung</p>
                        <p class="text-sm text-blue-700 dark:text-blue-400">
                            Excel (.xlsx, .xls) atau CSV dengan kolom:
                            <code class="rounded bg-blue-100 px-1.5 py-0.5 font-mono text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">no, nama_lengkap, email, nomor_induk</code>
                        </p>
                        <p class="text-sm text-blue-700 dark:text-blue-400">
                            <strong>Format NIS:</strong> Harus diawali "NIS-" (contoh: <code class="rounded bg-blue-100 px-1.5 py-0.5 font-mono text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">NIS-12345</code>)
                        </p>
                    </div>
                </div>
            </div>

            <!-- FORM UPLOAD -->
                                        <div class="mt-3 pt-3 border-t border-blue-200 dark:border-blue-800">
                                <a href="{{ route('import.siswa.template') }}" class="inline-flex items-center gap-2 text-sm font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300 transition-colors">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Download Template Excel Resmi
                                </a>
                            </div>

            <form method="POST" action="{{ route('import.siswa.preview') }}" enctype="multipart/form-data">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pilih File Excel/CSV</label>
                    
                    <!-- Area Upload: Menggunakan <label> wrapper (Bebas artefak, lebih aksesibel) -->
                    <label for="file_siswa" class="relative flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-400 bg-gray-50 p-5 text-center transition-all hover:border-brand-500 hover:bg-brand-50/30 dark:border-gray-600 dark:bg-gray-800/50 dark:hover:border-brand-500 dark:hover:bg-brand-900/10 cursor-pointer">
                        
                        <!-- Input Asli: sr-only (tersembunyi secara visual tapi tetap fungsional untuk Laravel) -->
                        <input type="file" id="file_siswa" name="file_siswa" accept=".xlsx,.xls,.csv" required class="sr-only">
                        
                        <div class="pointer-events-none space-y-2">
                            <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-brand-100 text-brand-600 dark:bg-brand-900/30 dark:text-brand-400">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    <span class="text-brand-600 dark:text-brand-400">Klik untuk memilih file</span>
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Format .xlsx, .xls, atau .csv (Maksimal 2 MB)</p>
                            </div>
                            <!-- Tampilan Nama File (Tanpa background pill yang aneh) -->
                            <p id="file-name-display" class="hidden text-sm font-medium text-brand-600 dark:text-brand-400 mt-2"></p>
                        </div>
                    </label>

                    @error('file_siswa')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- TOMBOL AKSI -->
                <div class="flex items-center justify-end gap-3 pt-5 border-t border-gray-200 dark:border-gray-800">
                    <a href="/admin/dashboard" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center rounded-lg border border-transparent bg-brand-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                        Pratinjau Data
                        <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('file_siswa').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        const display = document.getElementById('file-name-display');
        if (fileName) {
            display.textContent = 'File terpilih: ' + fileName;
            display.classList.remove('hidden');
        } else {
            display.classList.add('hidden');
        }
    });
</script>
@endsection