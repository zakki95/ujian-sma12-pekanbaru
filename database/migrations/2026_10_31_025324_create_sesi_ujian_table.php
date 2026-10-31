<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesi_ujian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_ujian_id')->constrained('peserta_ujian')->cascadeOnDelete();
            $table->foreignId('token_ujian_id')->nullable()->constrained('token_ujian')->restrictOnDelete();
            $table->timestamp('waktu_mulai');
            $table->timestamp('waktu_selesai')->nullable();
            $table->integer('durasi_terpakai_detik')->nullable();
            $table->enum('status', ['aktif', 'selesai', 'kedaluwarsa', 'dikunci'])->default('aktif');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            // JAMINAN KONKURENSI: Hanya 1 sesi 'aktif' per peserta (Partial Unique Index PostgreSQL)
            $table->unique('peserta_ujian_id')->where('status', 'aktif');
            
            $table->index(['peserta_ujian_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesi_ujian');
    }
};