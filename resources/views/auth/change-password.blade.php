<x-layout title="Ubah Password">
    <div class="max-w-lg mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Ubah Password</h1>

        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="bg-white shadow rounded-lg p-6">
            @csrf
            <div class="mb-4">
                <label for="kata_sandi_lama" class="block text-sm font-medium text-gray-700 mb-1">Password Lama</label>
                <input type="password" id="kata_sandi_lama" name="kata_sandi_lama" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
            </div>

            <div class="mb-4">
                <label for="kata_sandi_baru" class="block text-sm font-medium text-gray-700 mb-1">Password Baru (min. 8 karakter)</label>
                <input type="password" id="kata_sandi_baru" name="kata_sandi_baru" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
            </div>

            <div class="mb-6">
                <label for="kata_sandi_baru_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                <input type="password" id="kata_sandi_baru_confirmation" name="kata_sandi_baru_confirmation" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
            </div>

            <div class="flex items-center justify-between">
                <a href="/dashboard" class="text-sm text-gray-600 hover:text-gray-900">← Kembali ke dashboard</a>
                <button type="submit"
                    class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 text-sm font-medium">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</x-layout>