<?php
namespace Database\Factories;
use App\Models\Kelas;
use Illuminate\Database\Eloquent\Factories\Factory;

class KelasFactory extends Factory
{
    protected $model = Kelas::class;
    public function definition(): array
    {
        $tingkat = $this->faker->randomElement(['X', 'XI', 'XII']);
        $jurusan = $this->faker->randomElement(['IPA', 'IPS', 'Bahasa']);
        $no = $this->faker->numberBetween(1, 5);
        return [
            'nama_kelas' => "{$tingkat} {$jurusan} {$no}",
            'tingkat' => $tingkat,
            'tahun_ajaran' => '2026/2027',
        ];
    }
}