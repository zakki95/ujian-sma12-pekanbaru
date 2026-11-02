<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Ujian SMA 12</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white shadow-lg rounded-lg p-8">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-primary-600">Sistem Ujian SMA 12</h1>
            <p class="text-sm text-gray-600 mt-2">Silakan masuk dengan akun Anda</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                @foreach ($errors->all() as $error)
                    <p class="text-sm">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                    placeholder="nama@sekolah.test">
            </div>

            <div class="mb-4">
                <label for="kata_sandi" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="kata_sandi" name="kata_sandi" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                    placeholder="Masukkan password">
            </div>

            <div class="mb-4 flex items-center">
                <input type="checkbox" id="remember" name="remember"
                    class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                <label for="remember" class="ml-2 block text-sm text-gray-900">Ingat saya</label>
            </div>

            <button type="submit"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                Masuk
            </button>
        </form>

        <p class="mt-4 text-center text-xs text-gray-500">
            © 2026 SMA 12 Pekanbaru. Sistem Ujian Online.
        </p>
    </div>
</body>
</html>