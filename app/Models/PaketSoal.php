<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaketSoal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'guru_id', 'mata_pelajaran_id', 'nama_paket',
        'deskripsi', 'durasi_menit', 'status'
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'guru_id');
    }

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function jadwalUjian(): HasMany
    {
        return $this->hasMany(JadwalUjian::class);
    }
}