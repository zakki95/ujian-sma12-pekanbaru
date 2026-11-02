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