<?php
namespace App\Policies;
use App\Models\Kelas;
use App\Models\Pengguna;

class KelasPolicy
{
    public function viewAny(Pengguna $user): bool { return in_array($user->peran, ['admin', 'guru']); }
    public function view(Pengguna $user, Kelas $kelas): bool { return in_array($user->peran, ['admin', 'guru']); }
    public function create(Pengguna $user): bool { return $user->peran === 'admin'; }
    public function update(Pengguna $user, Kelas $kelas): bool { return $user->peran === 'admin'; }
    public function delete(Pengguna $user, Kelas $kelas): bool { return $user->peran === 'admin'; }
}