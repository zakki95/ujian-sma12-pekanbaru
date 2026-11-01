<?php
$base = __DIR__;

echo "=== MEMBUAT MIDDLEWARE ===\n";

// 1. RoleMiddleware
$roleMiddleware = <<<'PHP'
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if (!in_array($request->user()->peran, $roles)) {
            abort(403, 'Akses ditolak: Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
PHP;

file_put_contents($base . '\app\Http\Middleware\RoleMiddleware.php', $roleMiddleware);
echo "✅ RoleMiddleware.php\n";

// 2. CheckStatusMiddleware
$statusMiddleware = <<<'PHP'
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckStatusMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->status !== 'aktif') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')->with('error', 'Akun Anda tidak aktif. Silakan hubungi administrator.');
        }

        return $next($request);
    }
}
PHP;

file_put_contents($base . '\app\Http\Middleware\CheckStatusMiddleware.php', $statusMiddleware);
echo "✅ CheckStatusMiddleware.php\n";

echo "\n=== MEMBUAT CONTROLLER ===\n";

// 3. AuthController
$authController = <<<'PHP'
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'kata_sandi' => ['required'],
        ]);

        $pengguna = Pengguna::where('email', $credentials['email'])->first();

        if (!$pengguna) {
            return back()->withErrors(['email' => 'Email tidak terdaftar.']);
        }

        if ($pengguna->status !== 'aktif') {
            return back()->withErrors(['email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.']);
        }

        if (!Hash::check($credentials['kata_sandi'], $pengguna->kata_sandi)) {
            return back()->withErrors(['kata_sandi' => 'Password salah.']);
        }

        Auth::login($pengguna, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended($this->redirectBasedOnRole($pengguna->peran));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectBasedOnRole(string $role): string
    {
        return match($role) {
            'admin' => '/admin/dashboard',
            'guru' => '/guru/dashboard',
            'siswa' => '/siswa/dashboard',
            default => '/',
        };
    }
}
PHP;

file_put_contents($base . '\app\Http\Controllers\AuthController.php', $authController);
echo "✅ AuthController.php\n";

// 4. AdminController
$adminController = <<<'PHP'
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }
}
PHP;

file_put_contents($base . '\app\Http\Controllers\AdminController.php', $adminController);
echo "✅ AdminController.php\n";

// 5. GuruController
$guruController = <<<'PHP'
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function dashboard()
    {
        return view('guru.dashboard');
    }
}
PHP;

file_put_contents($base . '\app\Http\Controllers\GuruController.php', $guruController);
echo "✅ GuruController.php\n";

// 6. SiswaController
$siswaController = <<<'PHP'
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function dashboard()
    {
        return view('siswa.dashboard');
    }
}
PHP;

file_put_contents($base . '\app\Http\Controllers\SiswaController.php', $siswaController);
echo "✅ SiswaController.php\n";

echo "\n=== MEMBUAT GATES (AuthServiceProvider) ===\n";

// 7. Update AuthServiceProvider
$authServiceProvider = <<<'PHP'
<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Pengguna;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot(): void
    {
        // Gate: Hanya admin yang bisa mengakses panel admin
        Gate::define('akses-admin', function (Pengguna $user) {
            return $user->peran === 'admin';
        });

        // Gate: Guru atau admin bisa mengelola soal
        Gate::define('kelola-soal', function (Pengguna $user) {
            return in_array($user->peran, ['guru', 'admin']);
        });

        // Gate: Hanya siswa yang bisa mengerjakan ujian
        Gate::define('mengerjakan-ujian', function (Pengguna $user) {
            return $user->peran === 'siswa';
        });

        // Gate: Admin bisa mengelola semua pengguna
        Gate::define('manage-users', function (Pengguna $user) {
            return $user->peran === 'admin';
        });
    }
}
PHP;

file_put_contents($base . '\app\Providers\AuthServiceProvider.php', $authServiceProvider);
echo "✅ AuthServiceProvider.php\n";

echo "\n=== MEMBUAT VIEW SEDERHANA ===\n";

// Create view directories
@mkdir($base . '\resources\views\auth', 0755, true);
@mkdir($base . '\resources\views\admin', 0755, true);
@mkdir($base . '\resources\views\guru', 0755, true);
@mkdir($base . '\resources\views\siswa', 0755, true);

// Login View
$loginView = <<<'BLADE'
<!DOCTYPE html>
<html>
<head>
    <title>Login - Ujian SMA 12</title>
</head>
<body>
    <h1>Login</h1>
    
    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div style="color: red;">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <label>Email:</label><br>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>
        <div>
            <label>Password:</label><br>
            <input type="password" name="kata_sandi" required>
        </div>
        <div>
            <label>
                <input type="checkbox" name="remember"> Ingat saya
            </label>
        </div>
        <button type="submit">Login</button>
    </form>
</body>
</html>
BLADE;

file_put_contents($base . '\resources\views\auth\login.blade.php', $loginView);
echo "✅ login.blade.php\n";

// Admin Dashboard
$adminDashboard = <<<'BLADE'
<!DOCTYPE html>
<html>
<head><title>Admin Dashboard</title></head>
<body>
    <h1>Dashboard Admin</h1>
    <p>Selamat datang, {{ auth()->user()->nama_lengkap }} ({{ auth()->user()->peran }})</p>
    <p>Email: {{ auth()->user()->email }}</p>
    
    <h2>Menu Admin:</h2>
    <ul>
        <li>Kelola Pengguna</li>
        <li>Kelola Kelas</li>
        <li>Kelola Mata Pelajaran</li>
        <li>Lihat Semua Ujian</li>
    </ul>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
BLADE;

file_put_contents($base . '\resources\views\admin\dashboard.blade.php', $adminDashboard);
echo "✅ admin/dashboard.blade.php\n";

// Guru Dashboard
$guruDashboard = <<<'BLADE'
<!DOCTYPE html>
<html>
<head><title>Guru Dashboard</title></head>
<body>
    <h1>Dashboard Guru</h1>
    <p>Selamat datang, {{ auth()->user()->nama_lengkap }} ({{ auth()->user()->peran }})</p>
    <p>Mata Pelajaran: {{ auth()->user()->mataPelajaran->pluck('nama_mapel')->join(', ') }}</p>
    
    <h2>Menu Guru:</h2>
    <ul>
        <li>Kelola Bank Soal</li>
        <li>Buat Paket Soal</li>
        <li>Lihat Hasil Ujian Siswa</li>
    </ul>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
BLADE;

file_put_contents($base . '\resources\views\guru\dashboard.blade.php', $guruDashboard);
echo "✅ guru/dashboard.blade.php\n";

// Siswa Dashboard
$siswaDashboard = <<<'BLADE'
<!DOCTYPE html>
<html>
<head><title>Siswa Dashboard</title></head>
<body>
    <h1>Dashboard Siswa</h1>
    <p>Selamat datang, {{ auth()->user()->nama_lengkap }} ({{ auth()->user()->peran }})</p>
    <p>Kelas: {{ auth()->user()->anggotaKelas->first()?->kelas->nama_kelas ?? 'Belum ada kelas' }}</p>
    
    <h2>Menu Siswa:</h2>
    <ul>
        <li>Daftar Ujian Tersedia</li>
        <li>Lihat Hasil Ujian</li>
    </ul>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
BLADE;

file_put_contents($base . '\resources\views\siswa\dashboard.blade.php', $siswaDashboard);
echo "✅ siswa/dashboard.blade.php\n";

echo "\n=== MEMBUAT DOKUMENTASI ===\n";

// Documentation
$docContent = <<<'MD'
# Prosedur Pembuatan Admin Pertama

## ⚠️ PENTING: Jangan Hardcode Password Produksi!

Dokumen ini menjelaskan cara aman membuat akun admin pertama tanpa membocorkan rahasia.

## Metode 1: Menggunakan Artisan Tinker (Direkomendasikan)

1. Jalankan perintah berikut di terminal:
```bash
php artisan tinker