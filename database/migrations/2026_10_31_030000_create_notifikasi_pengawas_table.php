<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('notifikasi_pengawas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengawas_id')->constrained('pengguna')->cascadeOnDelete();
            $table->foreignId('sesi_ujian_id')->constrained('sesi_ujian')->cascadeOnDelete();
            $table->string('jenis_notifikasi', 100);
            $table->text('pesan');
            $table->boolean('status_baca')->default(false);
            $table->timestamp('created_at');
            $table->index(['pengawas_id', 'status_baca', 'created_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('notifikasi_pengawas'); }
};