<?php
namespace Database\Factories;
use App\Models\Kelas;
use Illuminate\Database\Eloquent\Factories\Factory;

class KelasFactory extends Factory
{
    protected $model = Kelas::class;
    public function definition(): array
    {
        $uniqueId = $this->faker->unique()->numberBetween(1000, 9999);
        return [
            'nama_kelas' => "Kelas Uji {$uniqueId}",
            'tingkat' => 'X',
            'tahun_ajaran' => '2026/2027',
        ];
    }
}