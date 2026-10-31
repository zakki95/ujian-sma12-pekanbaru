<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_soal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('pengguna')->restrictOnDelete();
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->restrictOnDelete();
            $table->enum('jenis_soal', ['pg', 'pg_kompleks', 'benar_salah', 'esai']);
            $table->text('teks_soal');
            $table->string('gambar_soal', 255)->nullable();
            $table->json('kunci_jawaban')->nullable();
            $table->text('rubrik_esai')->nullable();
            $table->decimal('bobot_nilai', 5, 2)->default(1.00);
            $table->enum('tingkat_kesukaran', ['mudah', 'sedang', 'sulit'])->nullable();
            $table->enum('status', ['draft', 'aktif', 'arsip'])->default('draft');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['guru_id', 'mata_pelajaran_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_soal');
    }
};