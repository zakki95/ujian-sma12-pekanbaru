<?php
namespace Database\Factories;
use App\Models\Ujian;
use Illuminate\Database\Eloquent\Factories\Factory;

class UjianFactory extends Factory
{
    protected $model = Ujian::class;
    public function definition(): array
    {
        return [
            'nama_ujian' => 'Ujian ' . $this->faker->randomElement(['Tengah', 'Akhir']) . ' Semester',
            'deskripsi' => $this->faker->sentence(),
            'tahun_ajaran' => '2026/2027',
            'semester' => $this->faker->randomElement(['ganjil', 'genap']),
            'status' => 'aktif',
        ];
    }
}