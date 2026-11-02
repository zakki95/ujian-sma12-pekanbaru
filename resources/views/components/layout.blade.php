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