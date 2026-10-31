<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('berkas_soal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('pengguna')->restrictOnDelete();
            $table->string('nama_berkas', 255);
            $table->string('path_berkas', 255);
            $table->unsignedBigInteger('ukuran_berkas');
            $table->string('mime_type', 100);
            $table->enum('status_impor', ['unggah', 'validasi', 'pratinjau', 'selesai', 'gagal']);
            $table->json('ringkasan_impor')->nullable();
            $table->timestamps();
            $table->index(['guru_id', 'status_impor']);
        });
    }
    public function down(): void { Schema::dropIfExists('berkas_soal'); }
};