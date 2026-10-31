<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ujian extends Model
{
    protected $table = 'ujian';
    use HasFactory;

    protected $fillable = [
        'nama_ujian', 'deskripsi', 'tahun_ajaran', 'semester', 'status'
    ];

    public function jadwalUjian(): HasMany
    {
        return $this->hasMany(JadwalUjian::class);
    }
}