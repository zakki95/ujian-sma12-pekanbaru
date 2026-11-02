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