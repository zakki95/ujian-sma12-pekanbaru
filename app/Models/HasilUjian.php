<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HasilUjian extends Model
{
    use HasFactory;

    protected $fillable = [
        'peserta_ujian_id', 'nilai_objektif', 'nilai_esai', 'nilai_akhir',
        'status_nilai', 'dipublikasikan', 'dipublikasikan_oleh', 'waktu_publikasi'
    ];

    protected $casts = [
        'nilai_objektif' => 'decimal:2',
        'nilai_esai' => 'decimal:2',
        'nilai_akhir' => 'decimal:2',
        'dipublikasikan' => 'boolean',
        'waktu_publikasi' => 'datetime',
    ];

    public function pesertaUjian(): BelongsTo
    {
        return $this->belongsTo(PesertaUjian::class);
    }

    public function dipublikasikanOleh(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'dipublikasikan_oleh');
    }

    public function rincianNilai(): HasMany
    {
        return $this->hasMany(RincianNilai::class);
    }
}