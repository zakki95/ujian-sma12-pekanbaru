<?php
$base = __DIR__;

echo "=== 1. PERBAIKAN LIVEWIRE COMPONENT (Layout & Validasi) ===\n";
file_put_contents($base . '\app\Livewire\KelasManager.php', <<<'PHP'
<?php

namespace App\Livewire;

use App\Models\Kelas;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layout')]
class KelasManager extends Component
{
    use WithPagination;

    public $search = '';
    public $nama_kelas = '';
    public $tingkat = '';
    public $tahun_ajaran = '';
    public $editId = null;
    public $showModal = false;

    protected function rules()
    {
        return [
            'nama_kelas' => [
                'required',
                'string',
                'max:50',
                Rule::unique('kelas', 'nama_kelas')->ignore($this->editId),
            ],
            'tingkat' => 'required|in:X,XI,XII',
            'tahun_ajaran' => ['required', 'string', 'regex:/^\d{4}\/\d{4}$/'],
        ];
    }

    protected $messages = [
        'tahun_ajaran.regex' => 'Format tahun ajaran harus YYYY/YYYY (contoh: 2026/2027).',
        'nama_kelas.unique' => 'Nama kelas sudah ada di database.',
    ];

    public function updatingSearch() { $this->resetPage(); }

    public function openModal($id = null)
    {
        $this->resetErrorBag();
        if ($id) {
            $kelas = Kelas::findOrFail($id);
            $this->editId = $id;
            $this->nama_kelas = $kelas->nama_kelas;
            $this->tingkat = $kelas->tingkat;
            $this->tahun_ajaran = $kelas->tahun_ajaran;
        } else {
            $this->editId = null;
            $this->nama_kelas = '';
            $this->tingkat = '';
            $this->tahun_ajaran = '';
        }
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['nama_kelas', 'tingkat', 'tahun_ajaran', 'editId']);
    }

    public function save()
    {
        $this->validate();
        if ($this->editId) {
            $kelas = Kelas::findOrFail($this->editId);
            $kelas->update(['nama_kelas' => $this->nama_kelas, 'tingkat' => $this->tingkat, 'tahun_ajaran' => $this->tahun_ajaran]);
            session()->flash('sukses', 'Kelas berhasil diperbarui.');
        } else {
            Kelas::create(['nama_kelas' => $this->nama_kelas, 'tingkat' => $this->tingkat, 'tahun_ajaran' => $this->tahun_ajaran]);
            session()->flash('sukses', 'Kelas berhasil dibuat.');
        }
        $this->closeModal();
    }

    public function delete($id)
    {
        $kelas = Kelas::findOrFail($id);
        $adaSiswaAktif = DB::table('anggota_kelas')->where('kelas_id', $kelas->id)->where('status', 'aktif')->exists();
        if ($adaSiswaAktif) {
            session()->flash('error_delete', 'Gagal hapus: Masih ada siswa aktif di kelas ini.');
            return;
        }
        $kelas->delete();
        session()->flash('sukses', 'Kelas berhasil dihapus.');
    }

    public function render()
    {
        $query = Kelas::query();
        if ($this->search) {
            $query->where('nama_kelas', 'like', '%' . $this->search . '%')->orWhere('tahun_ajaran', 'like', '%' . $this->search . '%');
        }
        $kelas = $query->orderBy('nama_kelas')->paginate(10);
        return view('livewire.kelas-manager', ['kelas' => $kelas]);
    }
}
PHP);
echo "OK KelasManager.php diperbaiki (Layout & validasi unique)\n";

echo "=== 2. PERBAIKAN TEST (Assertion yang Benar) ===\n";
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
        
        // Test Livewire component: delete harus gagal dan set session flash error
        Livewire::actingAs($this->admin())
            ->test(KelasManager::class)
            ->call('delete', $kelas->id)
            ->assertHasFlash('error_delete', 'Gagal hapus: Masih ada siswa aktif di kelas ini.');
            
        // Pastikan data kelas TIDAK terhapus di database
        $this->assertDatabaseHas('kelas', ['id' => $kelas->id]);
    }
}
PHP);
echo "OK Test diperbaiki (assertHasFlash untuk session flash)\n";

echo "\nSELESAI! Jalankan: php artisan test --filter=MasterDataAkademikTest\n";