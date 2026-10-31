<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peserta_ujian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_ujian_id')->constrained('jadwal_ujian')->cascadeOnDelete();
            $table->foreignId('pengguna_id')->constrained('pengguna')->cascadeOnDelete();
            $table->enum('status', ['terdaftar', 'belum_mulai', 'sedang_mengerjakan', 'selesai', 'dikecualikan'])->default('terdaftar');
            $table->timestamps();
            $table->unique(['jadwal_ujian_id', 'pengguna_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('peserta_ujian'); }
};
