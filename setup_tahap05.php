<?php
$base = __DIR__;
@mkdir($base . '\app\Http\Middleware', 0755, true);
@mkdir($base . '\app\Http\Controllers', 0755, true);
@mkdir($base . '\app\Providers', 0755, true);
@mkdir($base . '\resources\views\auth', 0755, true);
@mkdir($base . '\resources\views\admin', 0755, true);
@mkdir($base . '\resources\views\guru', 0755, true);
@mkdir($base . '\resources\views\siswa', 0755, true);
@mkdir($base . '\app\Console\Commands', 0755, true);
@mkdir($base . '\docs', 0755, true);

echo "=== MIDDLEWARE ===\n";

file_put_contents($base . '\app\Http\Middleware\RoleMiddleware.php', <<<'PHP'
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
            abort(403, 'Akses ditolak: peran Anda tidak memiliki izin.');
        }

        return $next($request);
    }
}
PHP);
echo "OK RoleMiddleware\n";

file_put_contents($base . '\app\Http\Middleware\CheckStatusMiddleware.php', <<<'PHP'
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

            return redirect()->route('login')->withErrors(['email' => 'Akun Anda tidak aktif. Hubungi administrator.']);
        }

        return $next($request);
    }
}
PHP);
echo "OK CheckStatusMiddleware\n";

echo "=== CONTROLLER ===\n";

file_put_contents($base . '\app\Http\Controllers\AuthController.php', <<<'PHP'
<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        if (!$pengguna || !Hash::check($credentials['kata_sandi'], $pengguna->kata_sandi)) {
            return back()->withErrors(['email' => 'Kredensial tidak cocok.'])->onlyInput('email');
        }

        if ($pengguna->status !== 'aktif') {
            return back()->withErrors(['email' => 'Akun Anda tidak aktif. Hubungi administrator.'])->onlyInput('email');
        }

        Auth::login($pengguna, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'kata_sandi_lama' => ['required'],
            'kata_sandi_baru' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();

        if (!Hash::check($request->kata_sandi_lama, $user->kata_sandi)) {
            return back()->withErrors(['kata_sandi_lama' => 'Password lama tidak sesuai.']);
        }

        $user->update(['kata_sandi' => Hash::make($request->kata_sandi_baru)]);

        return redirect('/dashboard')->with('sukses', 'Password berhasil diubah.');
    }
}
PHP);
echo "OK AuthController\n";

file_put_contents($base . '\app\Http\Controllers\AdminController.php', <<<'PHP'
<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }
}
PHP);

file_put_contents($base . '\app\Http\Controllers\GuruController.php', <<<'PHP'
<?php

namespace App\Http\Controllers;

class GuruController extends Controller
{
    public function dashboard()
    {
        return view('guru.dashboard');
    }
}
PHP);

file_put_contents($base . '\app\Http\Controllers\SiswaController.php', <<<'PHP'
<?php

namespace App\Http\Controllers;

class SiswaController extends Controller
{
    public function dashboard()
    {
        return view('siswa.dashboard');
    }
}
PHP);
echo "OK Admin/Guru/Siswa Controller\n";

echo "=== PROVIDER, GATES, BOOTSTRAP ===\n";

file_put_contents($base . '\app\Providers\AuthServiceProvider.php', <<<'PHP'
<?php

namespace App\Providers;

use App\Models\Pengguna;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::define('akses-admin', fn (Pengguna $user) => $user->peran === 'admin');
        Gate::define('kelola-soal', fn (Pengguna $user) => in_array($user->peran, ['guru', 'admin']));
        Gate::define('mengerjakan-ujian', fn (Pengguna $user) => $user->peran === 'siswa');
        Gate::define('manage-users', fn (Pengguna $user) => $user->peran === 'admin');
    }
}
PHP);
echo "OK AuthServiceProvider (gates)\n";

file_put_contents($base . '\bootstrap\providers.php', <<<'PHP'
<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
];
PHP);
echo "OK bootstrap/providers.php\n";

file_put_contents($base . '\bootstrap\app.php', <<<'PHP'
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'check.status' => \App\Http\Middleware\CheckStatusMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
PHP);
echo "OK bootstrap/app.php (alias middleware)\n";

echo "=== ROUTES ===\n";

file_put_contents($base . '\routes\web.php', <<<'PHP'
<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['auth', 'check.status'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/ubah-password', [AuthController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/ubah-password', [AuthController::class, 'changePassword'])->name('password.update');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    });

    Route::middleware('role:guru,admin')->prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', [GuruController::class, 'dashboard'])->name('dashboard');
    });

    Route::middleware('role:siswa')->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('dashboard');
    });
});

Route::get('/dashboard', function () {
    return redirect()->route(auth()->user()->peran . '.dashboard');
})->middleware(['auth', 'check.status']);

Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : redirect()->route('login');
});
PHP);
echo "OK routes/web.php\n";

echo "=== MODEL PENGGUNA (dengan relasi) ===\n";

file_put_contents($base . '\app\Models\Pengguna.php', <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'pengguna';

    protected $fillable = [
        'nama_lengkap', 'email', 'kata_sandi', 'peran',
        'nomor_induk', 'telepon', 'foto', 'status',
    ];

    protected $hidden = ['kata_sandi'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'kata_sandi' => 'hashed',
        ];
    }

    public function getAuthPasswordName()
    {
        return 'kata_sandi';
    }

    public function anggotaKelas(): HasMany
    {
        return $this->hasMany(AnggotaKelas::class, 'pengguna_id');
    }

    public function kelas(): BelongsToMany
    {
        return $this->belongsToMany(Kelas::class, 'anggota_kelas', 'pengguna_id', 'kelas_id');
    }

    public function guruMapel(): HasMany
    {
        return $this->hasMany(GuruMapel::class, 'pengguna_id');
    }

    public function mataPelajaran(): BelongsToMany
    {
        return $this->belongsToMany(MataPelajaran::class, 'guru_mapel', 'pengguna_id', 'mata_pelajaran_id');
    }
}
PHP);
echo "OK Pengguna.php\n";

echo "=== MIGRASI sessions & password_reset_tokens ===\n";

file_put_contents($base . '\database\migrations\2026_11_01_000000_create_sessions_dan_password_reset_tokens_table.php', <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
    }
};
PHP);
echo "OK migrasi sessions & password_reset_tokens\n";

echo "=== COMMAND RESET PASSWORD ===\n";

file_put_contents($base . '\app\Console\Commands\ResetPassword.php', <<<'PHP'
<?php

namespace App\Console\Commands;

use App\Models\Pengguna;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetPassword extends Command
{
    protected $signature = 'app:reset-password {email}';
    protected $description = 'Reset password pengguna secara aman (input tersembunyi, tanpa email/SMTP)';

    public function handle(): int
    {
        $user = Pengguna::where('email', $this->argument('email'))->first();

        if (!$user) {
            $this->error('Pengguna dengan email tersebut tidak ditemukan.');
            return self::FAILURE;
        }

        $baru = $this->secret('Password baru (minimal 8 karakter)');

        if (strlen($baru) < 8) {
            $this->error('Password minimal 8 karakter.');
            return self::FAILURE;
        }

        $user->update(['kata_sandi' => Hash::make($baru)]);
        $this->info('Password untuk ' . $user->email . ' berhasil direset.');

        return self::SUCCESS;
    }
}
PHP);
echo "OK ResetPassword.php\n";

echo "=== VIEWS ===\n";

file_put_contents($base . '\resources\views\auth\login.blade.php', <<<'BLADE'
<!DOCTYPE html>
<html lang="id">
<head><meta charset="utf-8"><title>Login - Ujian SMA 12</title></head>
<body>
<h1>Login Sistem Ujian</h1>

@if ($errors->any())
    <div style="color:red"><ul>
        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul></div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf
    <p><label>Email<br><input type="email" name="email" value="{{ old('email') }}" required></label></p>
    <p><label>Password<br><input type="password" name="kata_sandi" required></label></p>
    <p><label><input type="checkbox" name="remember"> Ingat saya</label></p>
    <button type="submit">Masuk</button>
</form>
</body>
</html>
BLADE);

file_put_contents($base . '\resources\views\auth\change-password.blade.php', <<<'BLADE'
<!DOCTYPE html>
<html lang="id">
<head><meta charset="utf-8"><title>Ubah Password</title></head>
<body>
<h1>Ubah Password</h1>

@if ($errors->any())
    <div style="color:red"><ul>
        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul></div>
@endif

<form method="POST" action="{{ route('password.update') }}">
    @csrf
    <p><label>Password lama<br><input type="password" name="kata_sandi_lama" required></label></p>
    <p><label>Password baru (min. 8 karakter)<br><input type="password" name="kata_sandi_baru" required></label></p>
    <p><label>Konfirmasi password baru<br><input type="password" name="kata_sandi_baru_confirmation" required></label></p>
    <button type="submit">Simpan</button>
</form>
<p><a href="/dashboard">Kembali ke dashboard</a></p>
</body>
</html>
BLADE);

file_put_contents($base . '\resources\views\admin\dashboard.blade.php', <<<'BLADE'
<!DOCTYPE html>
<html lang="id">
<head><meta charset="utf-8"><title>Dashboard Admin</title></head>
<body>
<h1>Dashboard Admin</h1>
<p>Selamat datang, <strong>{{ auth()->user()->nama_lengkap }}</strong> ({{ auth()->user()->peran }})</p>
@if (session('sukses'))<p style="color:green">{{ session('sukses') }}</p>@endif
<ul>
    <li>Kelola Pengguna</li>
    <li>Kelola Kelas & Mata Pelajaran</li>
    <li>Pantau Seluruh Ujian</li>
</ul>
<p><a href="{{ route('password.change') }}">Ubah Password</a></p>
<form method="POST" action="{{ route('logout') }}" style="display:inline">@csrf<button>Logout</button></form>
</body>
</html>
BLADE);

file_put_contents($base . '\resources\views\guru\dashboard.blade.php', <<<'BLADE'
<!DOCTYPE html>
<html lang="id">
<head><meta charset="utf-8"><title>Dashboard Guru</title></head>
<body>
<h1>Dashboard Guru</h1>
<p>Selamat datang, <strong>{{ auth()->user()->nama_lengkap }}</strong> ({{ auth()->user()->peran }})</p>
<p>Mata pelajaran diampu: {{ auth()->user()->mataPelajaran->pluck('nama_mapel')->join(', ') ?: '-' }}</p>
<ul>
    <li>Kelola Bank Soal</li>
    <li>Buat Paket Soal & Jadwal</li>
    <li>Koreksi Esai & Publikasi Nilai</li>
</ul>
<p><a href="{{ route('password.change') }}">Ubah Password</a></p>
<form method="POST" action="{{ route('logout') }}" style="display:inline">@csrf<button>Logout</button></form>
</body>
</html>
BLADE);

file_put_contents($base . '\resources\views\siswa\dashboard.blade.php', <<<'BLADE'
<!DOCTYPE html>
<html lang="id">
<head><meta charset="utf-8"><title>Dashboard Siswa</title></head>
<body>
<h1>Dashboard Siswa</h1>
<p>Selamat datang, <strong>{{ auth()->user()->nama_lengkap }}</strong> ({{ auth()->user()->peran }})</p>
<p>Kelas: {{ auth()->user()->kelas->pluck('nama_kelas')->join(', ') ?: 'Belum ada kelas' }}</p>
<ul>
    <li>Ujian Tersedia</li>
    <li>Riwayat & Hasil Ujian</li>
</ul>
<p><a href="{{ route('password.change') }}">Ubah Password</a></p>
<form method="POST" action="{{ route('logout') }}" style="display:inline">@csrf<button>Logout</button></form>
</body>
</html>
BLADE);
echo "OK 5 views\n";

echo "=== DOKUMENTASI ===\n";

file_put_contents($base . '\docs\ADMIN_SETUP.md', <<<'MD'
# Prosedur Akun Admin Pertama & Reset Password

## Prinsip Keamanan
- Password TIDAK BOLEH hardcode di kode sumber, `.env`, atau dokumentasi.
- Semua password disimpan sebagai hash bcrypt melalui `Hash::make()`.
- Input password di terminal menggunakan input tersembunyi (`$this->secret()`).

## Membuat Admin Pertama (Produksi / Lingkungan Baru)
Jalankan command interaktif berikut di server/terminal yang memiliki akses `.env`:
```bash
php artisan app:create-admin