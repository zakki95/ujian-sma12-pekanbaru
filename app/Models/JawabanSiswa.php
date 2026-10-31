<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JawabanSiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'sesi_ujian_id', 'soal_ujian_id', 'jawaban', 'waktu_jawab',
        'status_koreksi', 'nilai_diperoleh', 'catatan_koreksi', 'dikoreksi_oleh'
    ];

    protected $casts = [
        'jawaban' => 'array',
        'waktu_jawab' => 'datetime',
        'nilai_diperoleh' => 'decimal:2',
    ];

    public function sesiUjian(): BelongsTo
    {
        return $this->belongsTo(SesiUjian::class);
    }

    public function soalUjian(): BelongsTo
    {
        return $this->belongsTo(SoalUjian::class);
    }

    public function dikoreksiOleh(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'dikoreksi_oleh');
    }
}