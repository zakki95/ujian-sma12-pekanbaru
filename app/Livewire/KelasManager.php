<?php

namespace App\Livewire;

use App\Models\Kelas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
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
    public $errorHapus = ''; // Property untuk testing error penghapusan

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
        $this->errorHapus = '';
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
        // Otorisasi: Cek policy berdasarkan aksi (create atau update)
        if ($this->editId) {
            Gate::authorize('update', Kelas::findOrFail($this->editId));
        } else {
            Gate::authorize('create', Kelas::class);
        }

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
        $this->errorHapus = '';
        $kelas = Kelas::findOrFail($id);
        
        Gate::authorize('delete', $kelas);

        $adaSiswaAktif = DB::table('anggota_kelas')->where('kelas_id', $kelas->id)->where('status', 'aktif')->exists();
        if ($adaSiswaAktif) {
            $this->errorHapus = 'Gagal hapus: Masih ada siswa aktif di kelas ini.';
            session()->flash('error_delete', $this->errorHapus);
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