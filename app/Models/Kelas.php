<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    protected $table = 'kelas';
    use HasFactory;

    protected $fillable = ['nama_kelas', 'tingkat', 'tahun_ajaran', 'wali_kelas_id'];

    public function waliKelas(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'wali_kelas_id');
    }

    public function anggotaKelas(): HasMany
    {
        return $this->hasMany(AnggotaKelas::class);
    }
}