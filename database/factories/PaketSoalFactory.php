<?php
namespace Database\Factories;
use App\Models\PaketSoal;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaketSoalFactory extends Factory
{
    protected $model = PaketSoal::class;
    public function definition(): array
    {
        return [
            'nama_paket' => 'Paket ' . $this->faker->word(),
            'deskripsi' => $this->faker->sentence(),
            'durasi_menit' => 90,
            'status' => 'aktif',
        ];
    }
}