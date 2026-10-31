<?php
namespace Database\Factories;
use App\Models\HasilUjian;
use Illuminate\Database\Eloquent\Factories\Factory;

class HasilUjianFactory extends Factory
{
    protected $model = HasilUjian::class;
    public function definition(): array
    {
        $nilai = $this->faker->numberBetween(60, 100);
        return [
            'nilai_objektif' => $nilai,
            'nilai_esai' => null,
            'nilai_akhir' => $nilai,
            'status_nilai' => 'sementara',
            'dipublikasikan' => false,
        ];
    }
}