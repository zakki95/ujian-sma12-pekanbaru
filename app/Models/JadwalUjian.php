<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JadwalUjian extends Model
{
    protected $table = 'jadwal_ujian';
    use HasFactory;

    protected $fillable = [
        'ujian_id', 'mata_pelajaran_id', 'paket_soal_id',
        'tanggal', 'waktu_mulai', 'waktu_selesai', 'ruang', 'status'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function ujian(): BelongsTo
    {
        return $this->belongsTo(Ujian::class);
    }

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function paketSoal(): BelongsTo
    {
        return $this->belongsTo(PaketSoal::class);
    }

    public function pesertaUjian(): HasMany
    {
        return $this->hasMany(PesertaUjian::class);
    }

    public function tokenUjian(): HasMany
    {
        return $this->hasMany(TokenUjian::class);
    }
}