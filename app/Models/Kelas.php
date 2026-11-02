<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas', 'tingkat', 'tahun_ajaran', 'wali_kelas_id',
    ];

    public function waliKelas(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'wali_kelas_id');
    }

    public function anggotaKelas(): HasMany
    {
        // Foreign key eksplisit ke kolom 'kelas_id' di tabel 'anggota_kelas'
        return $this->hasMany(AnggotaKelas::class, 'kelas_id', 'id');
    }
}