<?php
$base = __DIR__;

// 1. Perbaiki MataPelajaranFactory agar nama selalu unik
$mapelFactory = <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\MataPelajaran;
use Illuminate\Database\Eloquent\Factories\Factory;

class MataPelajaranFactory extends Factory
{
    protected $model = MataPelajaran::class;

    public function definition(): array
    {
        // Gunakan angka acak unik agar tidak pernah bentrok dengan "Matematika" dari seeder
        $uniqueId = $this->faker->unique()->numberBetween(1000, 9999);
        return [
            'nama_mapel' => 'Mapel Uji ' . $uniqueId,
            'kode_mapel' => 'MU-' . $uniqueId,
        ];
    }
}
PHP;
file_put_contents($base . '\database\factories\MataPelajaranFactory.php', $mapelFactory);
echo "✅ MataPelajaranFactory diperbaiki (nama unik).\n";

// 2. Perbaiki PenggunaFactory agar nomor_induk unik dan sesuai peran
$penggunaFactory = <<<'PHP'
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
        // Pastikan prefix NIP untuk guru, NIS untuk siswa/admin, dan angka benar-benar unik
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
PHP;
file_put_contents($base . '\database\factories\PenggunaFactory.php', $penggunaFactory);
echo "✅ PenggunaFactory diperbaiki (nomor_induk unik & sesuai peran).\n";

echo "\nSELESAI! Silakan jalankan ulang test.\n";