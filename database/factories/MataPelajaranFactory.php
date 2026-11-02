<?php
namespace Database\Factories;
use App\Models\MataPelajaran;
use Illuminate\Database\Eloquent\Factories\Factory;

class MataPelajaranFactory extends Factory
{
    protected $model = MataPelajaran::class;
    public function definition(): array
    {
        $uniqueId = $this->faker->unique()->numberBetween(1000, 9999);
        return [
            'nama_mapel' => "Mapel Uji {$uniqueId}",
            'kode_mapel' => "MU-{$uniqueId}",
        ];
    }
}