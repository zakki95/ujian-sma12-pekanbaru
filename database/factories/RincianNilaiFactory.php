<?php
namespace Database\Factories;
use App\Models\RincianNilai;
use Illuminate\Database\Eloquent\Factories\Factory;

class RincianNilaiFactory extends Factory
{
    protected $model = RincianNilai::class;
    public function definition(): array
    {
        $jumlah = 10;
        $benar = $this->faker->numberBetween(6, 10);
        return [
            'jenis_soal' => 'pg',
            'jumlah_soal' => $jumlah,
            'jumlah_benar' => $benar,
            'bobot' => 1.00,
            'nilai_diperoleh' => $benar * 1.00,
        ];
    }
}