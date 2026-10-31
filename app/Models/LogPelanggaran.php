<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogPelanggaran extends Model
{
    protected $table = 'log_pelanggaran';
    use HasFactory;

    protected $fillable = [];

    public function sesiUjian(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SesiUjian::class);
    }

    public function ditindakOleh(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DitindakOleh::class);
    }


}