<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jawaban_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesi_ujian_id')->constrained('sesi_ujian')->cascadeOnDelete();
            $table->foreignId('soal_ujian_id')->constrained('soal_ujian')->cascadeOnDelete();
            $table->json('jawaban')->nullable();
            $table->timestamp('waktu_jawab')->nullable();
            $table->enum('status_koreksi', ['belum_dikoreksi', 'sudah_dikoreksi'])->default('belum_dikoreksi');
            $table->decimal('nilai_diperoleh', 5, 2)->nullable();
            $table->text('catatan_koreksi')->nullable();
            $table->foreignId('dikoreksi_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->timestamps();

            // JAMINAN KONKURENSI: 1 jawaban per soal per sesi
            $table->unique(['sesi_ujian_id', 'soal_ujian_id'], 'unique_jawaban_per_sesi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jawaban_siswa');
    }
};