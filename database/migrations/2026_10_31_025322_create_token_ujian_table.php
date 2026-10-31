<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('token_ujian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_ujian_id')->constrained('jadwal_ujian')->cascadeOnDelete();
            $table->string('token', 64)->unique();
            $table->timestamp('berlaku_hingga');
            $table->enum('status', ['aktif', 'kedaluwarsa', 'dicabut'])->default('aktif');
            $table->timestamps();
            $table->index(['jadwal_ujian_id', 'status']);
        });
    }
    public function down(): void { Schema::dropIfExists('token_ujian'); }
};
