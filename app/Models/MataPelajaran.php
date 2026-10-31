<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MataPelajaran extends Model
{
    protected $table = 'mata_pelajaran';
    use HasFactory;

    protected $fillable = ['nama_mapel', 'kode_mapel'];

    public function guruMapel(): HasMany
    {
        return $this->hasMany(GuruMapel::class);
    }

    public function bankSoal(): HasMany
    {
        return $this->hasMany(BankSoal::class);
    }

    public function jadwalUjian(): HasMany
    {
        return $this->hasMany(JadwalUjian::class);
    }
}