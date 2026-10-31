<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TokenUjian extends Model
{
    protected $table = 'token_ujian';
    use HasFactory;

    protected $fillable = ['jadwal_ujian_id', 'token', 'berlaku_hingga', 'status'];

    protected $casts = [
        'berlaku_hingga' => 'datetime',
    ];

    public function jadwalUjian(): BelongsTo
    {
        return $this->belongsTo(JadwalUjian::class);
    }

    public function sesiUjian(): HasMany
    {
        return $this->hasMany(SesiUjian::class);
    }
}