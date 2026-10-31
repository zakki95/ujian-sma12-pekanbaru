<?php
namespace Database\Factories;
use App\Models\BankSoal;
use Illuminate\Database\Eloquent\Factories\Factory;

class BankSoalFactory extends Factory
{
    protected $model = BankSoal::class;
    public function definition(): array
    {
        return [
            'jenis_soal' => 'pg',
            'teks_soal' => $this->faker->sentence(15),
            'kunci_jawaban' => json_encode(['A']),
            'bobot_nilai' => 1.00,
            'tingkat_kesukaran' => $this->faker->randomElement(['mudah', 'sedang', 'sulit']),
            'status' => 'aktif',
        ];
    }
}