<?php
namespace Database\Factories;
use App\Models\Pengguna;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class PenggunaFactory extends Factory
{
    protected $model = Pengguna::class;
    public function definition(): array
    {
        $peran = $this->faker->randomElement(['admin', 'guru', 'siswa']);
        $prefix = $peran === 'guru' ? 'NIP' : 'NIS';
        $uniqueNum = $this->faker->unique()->numberBetween(100000, 999999);
        
        return [
            'nama_lengkap' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'kata_sandi' => Hash::make('password123'),
            'peran' => $peran,
            'nomor_induk' => $prefix . '-' . $uniqueNum,
            'telepon' => $this->faker->phoneNumber(),
            'status' => 'aktif',
        ];
    }
}