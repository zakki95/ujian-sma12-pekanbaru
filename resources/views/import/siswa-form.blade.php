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