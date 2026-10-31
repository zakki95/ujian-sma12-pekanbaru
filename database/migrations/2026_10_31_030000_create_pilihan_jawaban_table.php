<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pilihan_jawaban', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_soal_id')->constrained('bank_soal')->cascadeOnDelete();
            $table->string('label', 5);
            $table->text('teks_jawaban');
            $table->string('gambar_jawaban', 255)->nullable();
            $table->boolean('is_benar')->default(false);
            $table->integer('urutan');
            $table->timestamps();
            $table->index(['bank_soal_id', 'urutan']);
        });
    }
    public function down(): void { Schema::dropIfExists('pilihan_jawaban'); }
};