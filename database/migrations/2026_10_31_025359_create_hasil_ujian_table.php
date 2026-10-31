<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_ujian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_ujian_id')->unique()->constrained('peserta_ujian')->cascadeOnDelete();
            $table->decimal('nilai_objektif', 5, 2)->nullable();
            $table->decimal('nilai_esai', 5, 2)->nullable();
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->enum('status_nilai', ['sementara', 'final'])->default('sementara');
            $table->boolean('dipublikasikan')->default(false);
            $table->foreignId('dipublikasikan_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->timestamp('waktu_publikasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_ujian');
    }
};