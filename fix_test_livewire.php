<?php
$base = __DIR__;

echo "=== UPDATE TEST UNTUK LIVEWIRE ===\n";
file_put_contents($base . '\tests\Feature\MasterDataAkademikTest.php', <<<'PHP'
<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Pengguna;
use App\Models\AnggotaKelas;
use App\Livewire\KelasManager;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class MasterDataAkademikTest extends TestCase
{
    use DatabaseTransactions;
    
    private function admin() { return Pengguna::factory()->create(['peran' => 'admin']); }
    private function guru() { return Pengguna::factory()->create(['peran' => 'guru']); }
    private function siswa() { return Pengguna::factory()->create(['peran' => 'siswa']); }

    public function test_guru_tidak_bisa_membuat_kelas(): void
    {
        // Guru tidak bisa mengakses halaman /kelas (Livewire component)
        $this->actingAs($this->guru())->get('/kelas')->assertForbidden();
    }

    public function test_admin_bisa_membuat_kelas_dengan_validasi_unik(): void
    {
        Kelas::factory()->create(['nama_kelas' => 'X IPA 1']);
        
        // Test Livewire component: validasi nama_kelas harus unik
        Livewire::actingAs($this->admin())
            ->test(KelasManager::class)
            ->set('nama_kelas', 'X IPA 1')
            ->set('tingkat', 'X')
            ->set('tahun_ajaran', '2026/2027')
            ->call('save')
            ->assertHasErrors(['nama_kelas' => 'unique']);
    }

    public function test_guru_hanya_bisa_melihat_mapel_yang_diampunya(): void
    {
        $guru = $this->guru();
        $mapelLain = MataPelajaran::factory()->create();
        $mapelSendiri = MataPelajaran::factory()->create();
        $guru->mataPelajaran()->attach($mapelSendiri->id);
        $this->actingAs($guru)->get('/mata-pelajaran/' . $mapelSendiri->id)->assertOk();
        $this->actingAs($guru)->get('/mata-pelajaran/' . $mapelLain->id)->assertForbidden();
    }

    public function test_penghapusan_kelas_gagal_jika_masih_ada_siswa_aktif(): void
    {
        $kelas = Kelas::factory()->create();
        $siswa = $this->siswa();
        AnggotaKelas::create(['kelas_id' => $kelas->id, 'pengguna_id' => $siswa->id, 'status' => 'aktif']);
        
        // Test Livewire component: delete harus gagal dan set flash error
        Livewire::actingAs($this->admin())
            ->test(KelasManager::class)
            ->call('delete', $kelas->id)
            ->assertDispatched('refresh') // atau cek flash message
            ->assertSee('Gagal hapus');
            
        // Pastikan data kelas TIDAK terhapus di database
        $this->assertDatabaseHas('kelas', ['id' => $kelas->id]);
    }
}
PHP);
echo "OK Test diupdate untuk Livewire\n";

echo "\nSELESAI! Jalankan: php artisan test --filter=MasterDataAkademikTest\n";