<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rincian_nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hasil_ujian_id')->constrained('hasil_ujian')->cascadeOnDelete();
            $table->enum('jenis_soal', ['pg', 'pg_kompleks', 'benar_salah', 'esai']);
            $table->integer('jumlah_soal');
            $table->integer('jumlah_benar');
            $table->decimal('bobot', 5, 2);
            $table->decimal('nilai_diperoleh', 5, 2);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('rincian_nilai'); }
};
