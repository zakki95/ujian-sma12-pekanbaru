<?php

namespace App\Policies;

use App\Models\BankSoal;
use App\Models\Pengguna;

class BankSoalPolicy
{
    public function viewAny(Pengguna $user): bool
    {
        return in_array($user->peran, ['admin', 'guru']);
    }

    public function view(Pengguna $user, BankSoal $bankSoal): bool
    {
        return $user->peran === 'admin' || $bankSoal->guru_id === $user->id;
    }

    public function create(Pengguna $user): bool
    {
        return in_array($user->peran, ['admin', 'guru']);
    }

    public function update(Pengguna $user, BankSoal $bankSoal): bool
    {
        return $user->peran === 'admin' || $bankSoal->guru_id === $user->id;
    }

    public function delete(Pengguna $user, BankSoal $bankSoal): bool
    {
        return $user->peran === 'admin' || $bankSoal->guru_id === $user->id;
    }
}