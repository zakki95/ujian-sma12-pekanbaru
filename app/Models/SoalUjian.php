<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SoalUjian extends Model
{
    protected $table = 'soal_ujian';
    use HasFactory;

    protected $fillable = ['sesi_ujian_id', 'bank_soal_id', 'urutan'];

    public function sesiUjian(): BelongsTo
    {
        return $this->belongsTo(SesiUjian::class);
    }

    public function bankSoal(): BelongsTo
    {
        return $this->belongsTo(BankSoal::class);
    }

    public function jawabanSiswa(): HasMany
    {
        return $this->hasMany(JawabanSiswa::class);
    }
}