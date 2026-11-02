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

Route::middleware(['auth', 'check.status', 'role:admin,guru'])->get('/kelas', \App\Livewire\KelasManager::class)->name('kelas.index');
Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : redirect()->route('login');
});
// --- TAHAP 06: Master Data Akademik ---
Route::middleware(['auth', 'check.status', 'role:admin,guru'])->group(function () {
    
    Route::resource('mata-pelajaran', \App\Http\Controllers\MataPelajaranController::class)->only(['index', 'show']);
});

Route::middleware(['auth', 'check.status', 'role:admin'])->group(function () {
    Route::get('/import/siswa', [\App\Http\Controllers\ImportSiswaController::class, 'showForm'])->name('import.siswa.form');
    Route::post('/import/siswa/preview', [\App\Http\Controllers\ImportSiswaController::class, 'preview'])->name('import.siswa.preview');
    Route::post('/import/siswa/process', [\App\Http\Controllers\ImportSiswaController::class, 'process'])->name('import.siswa.process');
});