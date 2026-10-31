<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soal_ujian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesi_ujian_id')->constrained('sesi_ujian')->cascadeOnDelete();
            $table->foreignId('bank_soal_id')->constrained('bank_soal')->restrictOnDelete();
            $table->integer('urutan');
            $table->timestamps();
            $table->index(['sesi_ujian_id', 'urutan']);
        });
    }
    public function down(): void { Schema::dropIfExists('soal_ujian'); }
};
