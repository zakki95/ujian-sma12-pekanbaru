<?php
namespace Database\Factories;
use App\Models\Pengguna;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PenggunaFactory extends Factory
{
    protected $model = Pengguna::class;
    public function definition(): array
    {
        $peran = $this->faker->randomElement(['admin', 'guru', 'siswa']);
        $nomorInduk = $peran === 'guru' ? 'NIP-' . $this->faker->unique()->numberBetween(10000, 99999) : 'NIS-' . $this->faker->unique()->numberBetween(10000, 99999);
        
        return [
            'nama_lengkap' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'kata_sandi' => Hash::make('password123'), // Password default untuk semua user uji
            'peran' => $peran,
            'nomor_induk' => $nomorInduk,
            'telepon' => $this->faker->phoneNumber(),
            'status' => 'aktif',
        ];
    }
}