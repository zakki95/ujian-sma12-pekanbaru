<?php
namespace Database\Factories;
use App\Models\JawabanSiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

class JawabanSiswaFactory extends Factory
{
    protected $model = JawabanSiswa::class;
    public function definition(): array
    {
        return [
            'jawaban' => json_encode(['pilihan' => $this->faker->randomElement(['A', 'B', 'C', 'D'])]),
            'waktu_jawab' => now(),
            'status_koreksi' => 'sudah_dikoreksi',
            'nilai_diperoleh' => $this->faker->randomElement([0, 1, 1, 1]), // 75% chance correct
        ];
    }
}