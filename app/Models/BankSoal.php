<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankSoal extends Model
{
    protected $table = 'bank_soal';
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'guru_id', 'mata_pelajaran_id', 'jenis_soal', 'teks_soal',
        'gambar_soal', 'kunci_jawaban', 'rubrik_esai', 'bobot_nilai',
        'tingkat_kesukaran', 'status'
    ];

    protected $casts = [
        'kunci_jawaban' => 'array',
        'bobot_nilai' => 'decimal:2',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'guru_id');
    }

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function soalUjian(): HasMany
    {
        return $this->hasMany(SoalUjian::class);
    }
}