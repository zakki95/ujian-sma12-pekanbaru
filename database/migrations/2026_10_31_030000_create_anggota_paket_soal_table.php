<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('anggota_paket_soal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paket_soal_id')->constrained('paket_soal')->cascadeOnDelete();
            $table->foreignId('bank_soal_id')->constrained('bank_soal')->restrictOnDelete();
            $table->integer('urutan');
            $table->timestamps();
            $table->unique(['paket_soal_id', 'bank_soal_id']);
            $table->index(['paket_soal_id', 'urutan']);
        });
    }
    public function down(): void { Schema::dropIfExists('anggota_paket_soal'); }
};