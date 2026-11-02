<?php
namespace App\Policies;
use App\Models\MataPelajaran;
use App\Models\Pengguna;

class MataPelajaranPolicy
{
    public function viewAny(Pengguna $user): bool { return true; }
    public function view(Pengguna $user, MataPelajaran $mapel): bool { 
        return $user->peran === 'admin' || $user->mataPelajaran->contains($mapel->id);
    }
    public function create(Pengguna $user): bool { return $user->peran === 'admin'; }
    public function update(Pengguna $user, MataPelajaran $mapel): bool { 
        return $user->peran === 'admin' || $user->mataPelajaran->contains($mapel->id);
    }
    public function delete(Pengguna $user, MataPelajaran $mapel): bool { return $user->peran === 'admin'; }
}