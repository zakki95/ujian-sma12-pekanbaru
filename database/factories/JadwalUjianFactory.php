<?php
namespace Database\Factories;
use App\Models\JadwalUjian;
use Illuminate\Database\Eloquent\Factories\Factory;

class JadwalUjianFactory extends Factory
{
    protected $model = JadwalUjian::class;
    public function definition(): array
    {
        return [
            'tanggal' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'waktu_mulai' => '08:00:00',
            'waktu_selesai' => '10:00:00',
            'ruang' => 'Ruang ' . $this->faker->randomElement(['A1', 'B2', 'C3']),
            'status' => 'terjadwal',
        ];
    }
}