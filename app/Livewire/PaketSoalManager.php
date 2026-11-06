<?php

namespace App\Livewire;

use App\Models\PaketSoal;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.livewire')]
class PaketSoalManager extends Component
{
    use WithPagination;

    public $search = '';
    public $mapel_id = '';
    public $status = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingMapelId() { $this->resetPage(); }
    public function updatingStatus() { $this->resetPage(); }

    public function arsip($id)
    {
        $paket = PaketSoal::findOrFail($id);
        Gate::authorize('update', $paket);
        $paket->update(['status' => 'arsip']);
        session()->flash('sukses', 'Paket soal berhasil diarsipkan.');
    }

    public function hapusPermanen($id)
    {
        $paket = PaketSoal::findOrFail($id);
        Gate::authorize('delete', $paket);

        if ($paket->jadwalUjian()->exists()) {
            session()->flash('error', 'Gagal hapus: Paket soal sudah digunakan dalam jadwal ujian.');
            return;
        }

        $paket->forceDelete();
        session()->flash('sukses', 'Paket soal berhasil dihapus permanen.');
    }

    public function render()
    {
        Gate::authorize('viewAny', PaketSoal::class);

        $query = PaketSoal::with(['mataPelajaran', 'guru']);

        if (auth()->user()->peran === 'guru') {
            $query->where('guru_id', auth()->id());
        }

        if ($this->search) {
            $query->where('nama_paket', 'like', '%' . $this->search . '%');
        }
        if ($this->mapel_id) {
            $query->where('mata_pelajaran_id', $this->mapel_id);
        }
        if ($this->status) {
            $query->where('status', $this->status);
        }

        $pakets = $query->orderBy('created_at', 'desc')->paginate(10);
        $mapels = MataPelajaran::orderBy('nama_mapel')->get();

        return view('livewire.paket-soal-manager', [
            'pakets' => $pakets,
            'mapels' => $mapels
        ]);
    }
}