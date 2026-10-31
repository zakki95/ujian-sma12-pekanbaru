<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paket_soal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('pengguna')->restrictOnDelete();
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->restrictOnDelete();
            $table->string('nama_paket', 255);
            $table->text('deskripsi')->nullable();
            $table->integer('durasi_menit');
            $table->enum('status', ['draft', 'aktif', 'arsip'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void { Schema::dropIfExists('paket_soal'); }
};
