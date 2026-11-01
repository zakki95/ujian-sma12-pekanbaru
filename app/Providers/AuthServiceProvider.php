<?php

namespace App\Providers;

use App\Models\Pengguna;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot(): void
    {
        Gate::define('akses-admin', fn (Pengguna $user) => $user->peran === 'admin');
        Gate::define('kelola-soal', fn (Pengguna $user) => in_array($user->peran, ['guru', 'admin']));
        Gate::define('mengerjakan-ujian', fn (Pengguna $user) => $user->peran === 'siswa');
        Gate::define('manage-users', fn (Pengguna $user) => $user->peran === 'admin');
    }
}