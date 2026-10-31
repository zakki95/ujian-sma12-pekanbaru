<?php
namespace Database\Factories;
use App\Models\SoalUjian;
use Illuminate\Database\Eloquent\Factories\Factory;

class SoalUjianFactory extends Factory
{
    protected $model = SoalUjian::class;
    public function definition(): array
    {
        return [
            'urutan' => $this->faker->numberBetween(1, 20),
        ];
    }
}