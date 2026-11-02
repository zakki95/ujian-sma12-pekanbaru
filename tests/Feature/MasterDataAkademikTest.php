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

    public function test_guru_bisa_lihat_kelas_tapi_tidak_bisa_membuat(): void
    {
        // Guru boleh melihat halaman daftar kelas
        $this->actingAs($this->guru())->get('/kelas')->assertOk();
        
        // Tapi TIDAK BOLEH membuat kelas baru via Livewire (akan throw 403 Forbidden)
        Livewire::actingAs($this->guru())
            ->test(KelasManager::class)
            ->set('nama_kelas', 'X IPA 99')
            ->set('tingkat', 'X')
            ->set('tahun_ajaran', '2026/2027')
            ->call('save')
            ->assertForbidden();
    }

    public function test_admin_bisa_membuat_kelas_dengan_validasi_unik(): void
    {
        Kelas::factory()->create(['nama_kelas' => 'X IPA 1']);
        
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
        
        // Test Livewire component: delete gagal dan set property errorHapus
        Livewire::actingAs($this->admin())
            ->test(KelasManager::class)
            ->call('delete', $kelas->id)
            ->assertSet('errorHapus', 'Gagal hapus: Masih ada siswa aktif di kelas ini.');
            
        // Pastikan data kelas TIDAK terhapus di database
        $this->assertDatabaseHas('kelas', ['id' => $kelas->id]);
    }
}