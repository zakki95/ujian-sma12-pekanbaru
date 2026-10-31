<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas', 50)->unique();
            $table->enum('tingkat', ['X', 'XI', 'XII']);
            $table->string('tahun_ajaran', 20);
            $table->foreignId('wali_kelas_id')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->timestamps();
            $table->index(['tingkat', 'tahun_ajaran']);
        });
    }
    public function down(): void { Schema::dropIfExists('kelas'); }
};
