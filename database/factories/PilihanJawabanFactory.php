<?php
namespace Database\Factories;
use App\Models\PilihanJawaban;
use Illuminate\Database\Eloquent\Factories\Factory;

class PilihanJawabanFactory extends Factory
{
    protected $model = PilihanJawaban::class;
    public function definition(): array
    {
        return [
            'label' => $this->faker->randomElement(['A', 'B', 'C', 'D']),
            'teks_jawaban' => $this->faker->sentence(8),
            'is_benar' => false, // Akan dioverride di seeder untuk 1 jawaban benar
            'urutan' => $this->faker->numberBetween(1, 4),
        ];
    }
}