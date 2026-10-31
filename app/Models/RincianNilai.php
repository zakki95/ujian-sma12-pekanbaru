<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RincianNilai extends Model
{
    use HasFactory;

    protected $fillable = [
        'hasil_ujian_id', 'jenis_soal', 'jumlah_soal',
        'jumlah_benar', 'bobot', 'nilai_diperoleh'
    ];

    protected $casts = [
        'bobot' => 'decimal:2',
        'nilai_diperoleh' => 'decimal:2',
    ];

    public function hasilUjian(): BelongsTo
    {
        return $this->belongsTo(HasilUjian::class);
    }
}