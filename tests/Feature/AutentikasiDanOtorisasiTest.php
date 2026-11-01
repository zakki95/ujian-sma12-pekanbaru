<?php
namespace Tests\Feature;
use App\Models\Pengguna;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AutentikasiDanOtorisasiTest extends TestCase
{
    use DatabaseTransactions;

    private function buatPengguna(string $peran, string $status = 'aktif'): Pengguna
    {
        return Pengguna::create([
            'nama_lengkap' => 'Uji ' . ucfirst($peran),
            'email' => 'uji.' . $peran . '.' . uniqid() . '@sekolah.test',
            'kata_sandi' => Hash::make('rahasia123'),
            'peran' => $peran,
            'status' => $status,
        ]);
    }

    public function test_tamu_diarahkan_ke_login(): void {
        $this->get('/admin/dashboard')->assertRedirect('/login');
    }

    public function test_login_berhasil(): void {
        $user = $this->buatPengguna('siswa');
        $this->post('/login', ['email' => $user->email, 'kata_sandi' => 'rahasia123'])->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_siswa_ditolak_mengakses_url_admin(): void {
        $this->actingAs($this->buatPengguna('siswa'))->get('/admin/dashboard')->assertForbidden();
    }

    public function test_gate_kelola_soal_hanya_guru_dan_admin(): void {
        $this->assertTrue(Gate::forUser($this->buatPengguna('guru'))->allows('kelola-soal'));
        $this->assertFalse(Gate::forUser($this->buatPengguna('siswa'))->allows('kelola-soal'));
    }
}