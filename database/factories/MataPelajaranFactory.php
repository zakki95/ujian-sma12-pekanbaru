<?php

namespace Database\Factories;

use App\Models\MataPelajaran;
use Illuminate\Database\Eloquent\Factories\Factory;

class MataPelajaranFactory extends Factory
{
    protected $model = MataPelajaran::class;

    public function definition(): array
    {
        static $index = 0;
        $mapels = ['Matematika', 'Bahasa Indonesia', 'Fisika', 'Sejarah', 'Kimia', 'Biologi', 'Ekonomi', 'Sosiologi'];
        $mapel = $mapels[$index % count($mapels)];
        $index++;
        
        return [
            'nama_mapel' => $mapel,
            'kode_mapel' => strtoupper(substr($mapel, 0, 3)) . '-' . $this->faker->unique()->numberBetween(100, 999),
        ];
    }
}