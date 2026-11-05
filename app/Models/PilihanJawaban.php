<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PilihanJawaban extends Model
{
    protected $table = 'pilihan_jawaban';
    use HasFactory;

    protected $fillable = ['label', 'teks_jawaban', 'gambar_jawaban', 'is_benar', 'urutan'];

    public function bankSoal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BankSoal::class);
    }


}