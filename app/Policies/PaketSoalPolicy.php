<?php

namespace App\Policies;

use App\Models\PaketSoal;
use App\Models\Pengguna;

class PaketSoalPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Pengguna $user): bool
    {
        return in_array($user->peran, ['admin', 'guru'], true);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Pengguna $user, PaketSoal $paketSoal): bool
    {
        return $user->peran === 'admin' || $paketSoal->guru_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Pengguna $user): bool
    {
        return in_array($user->peran, ['admin', 'guru'], true);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Pengguna $user, PaketSoal $paketSoal): bool
    {
        return $user->peran === 'admin' || $paketSoal->guru_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Pengguna $user, PaketSoal $paketSoal): bool
    {
        return $user->peran === 'admin' || $paketSoal->guru_id === $user->id;
    }
}