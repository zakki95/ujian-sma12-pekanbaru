<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('log_pelanggaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesi_ujian_id')->constrained('sesi_ujian')->cascadeOnDelete();
            $table->string('jenis_pelanggaran', 100);
            $table->text('deskripsi')->nullable();
            $table->integer('durasi_detik')->nullable();
            $table->enum('tindakan', ['peringatan', 'dikunci', 'dibuka_kembali'])->nullable();
            $table->foreignId('ditindak_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->timestamp('created_at');
            $table->index(['sesi_ujian_id', 'jenis_pelanggaran', 'created_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('log_pelanggaran'); }
};