<?php
namespace Database\Factories;
use App\Models\SesiUjian;
use Illuminate\Database\Eloquent\Factories\Factory;

class SesiUjianFactory extends Factory
{
    protected $model = SesiUjian::class;
    public function definition(): array
    {
        return [
            'waktu_mulai' => now(),
            'durasi_terpakai_detik' => 0,
            'status' => 'aktif',
            'ip_address' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
        ];
    }
}