<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PesertaUjian extends Model
{
    protected $table = 'peserta_ujian';
    use HasFactory;

    protected $fillable = ['jadwal_ujian_id', 'pengguna_id', 'status'];

    public function jadwalUjian(): BelongsTo
    {
        return $this->belongsTo(JadwalUjian::class);
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class);
    }

    public function sesiUjian(): HasMany
    {
        return $this->hasMany(SesiUjian::class);
    }

    public function hasilUjian(): HasOne
    {
        return $this->hasOne(HasilUjian::class);
    }
}