<?php
namespace Tests\Feature;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Pengguna;
use App\Models\AnggotaKelas;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MasterDataAkademikTest extends TestCase
{
    use DatabaseTransactions;
    private function admin() { return Pengguna::factory()->create(['peran' => 'admin']); }
    private function guru() { return Pengguna::factory()->create(['peran' => 'guru']); }
    private function siswa() { return Pengguna::factory()->create(['peran' => 'siswa']); }

    public function test_guru_tidak_bisa_membuat_kelas(): void {
        $this->actingAs($this->guru())->post('/kelas', ['nama_kelas' => 'X IPA 99', 'tingkat' => 'X', 'tahun_ajaran' => '2026/2027'])->assertForbidden();
    }

    public function test_admin_bisa_membuat_kelas_dengan_validasi_unik(): void {
        Kelas::factory()->create(['nama_kelas' => 'X IPA 1']);
        $this->actingAs($this->admin())->post('/kelas', ['nama_kelas' => 'X IPA 1', 'tingkat' => 'X', 'tahun_ajaran' => '2026/2027'])->assertSessionHasErrors('nama_kelas');
    }

    public function test_guru_hanya_bisa_melihat_mapel_yang_diampunya(): void {
        $guru = $this->guru();
        $mapelLain = MataPelajaran::factory()->create();
        $mapelSendiri = MataPelajaran::factory()->create();
        $guru->mataPelajaran()->attach($mapelSendiri->id);
        $this->actingAs($guru)->get('/mata-pelajaran/' . $mapelSendiri->id)->assertOk();
        $this->actingAs($guru)->get('/mata-pelajaran/' . $mapelLain->id)->assertForbidden();
    }

    public function test_penghapusan_kelas_gagal_jika_masih_ada_siswa_aktif(): void {
        $kelas = Kelas::factory()->create();
        $siswa = $this->siswa();
        AnggotaKelas::create(['kelas_id' => $kelas->id, 'pengguna_id' => $siswa->id, 'status' => 'aktif']);
        
        // Uji penghapusan: Harus redirect dan membawa session 'error_delete'
        $this->actingAs($this->admin())->delete('/kelas/' . $kelas->id)
             ->assertRedirect(route('kelas.index'))
             ->assertSessionHas('error_delete');
             
        // Pastikan data kelas TIDAK terhapus di database
        $this->assertDatabaseHas('kelas', ['id' => $kelas->id]);
    }
}