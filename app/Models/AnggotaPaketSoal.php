<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaPaketSoal extends Model
{
    protected $table = 'anggota_paket_soal';
    use HasFactory;

    protected $fillable = [];

    public function paketSoal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PaketSoal::class);
    }

    public function bankSoal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BankSoal::class);
    }


}