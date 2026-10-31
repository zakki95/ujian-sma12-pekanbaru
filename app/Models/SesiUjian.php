<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SesiUjian extends Model
{
    use HasFactory;

    protected $fillable = [
        'peserta_ujian_id', 'token_ujian_id', 'waktu_mulai', 'waktu_selesai',
        'durasi_terpakai_detik', 'status', 'ip_address', 'user_agent'
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function pesertaUjian(): BelongsTo
    {
        return $this->belongsTo(PesertaUjian::class);
    }

    public function tokenUjian(): BelongsTo
    {
        return $this->belongsTo(TokenUjian::class);
    }

    public function soalUjian(): HasMany
    {
        return $this->hasMany(SoalUjian::class);
    }

    public function jawabanSiswa(): HasMany
    {
        return $this->hasMany(JawabanSiswa::class);
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}