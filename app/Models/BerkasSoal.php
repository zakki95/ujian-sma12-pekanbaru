<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasSoal extends Model
{
    protected $table = 'berkas_soal';
    use HasFactory;

    protected $fillable = ['nama_berkas', 'path_berkas', 'ukuran_berkas', 'mime_type', 'status_impor', 'ringkasan_impor'];

    public function guru(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'guru_id');
    }


}