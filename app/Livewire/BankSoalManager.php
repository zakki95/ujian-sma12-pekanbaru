<?php

namespace App\Livewire;

use App\Models\BankSoal;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.livewire')]
class BankSoalManager extends Component
{
    use WithPagination;

    public $search = '';
    public $mapel_id = '';
    public $jenis_soal = '';
    public $status = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingMapelId() { $this->resetPage(); }
    public function updatingJenisSoal() { $this->resetPage(); }
    public function updatingStatus() { $this->resetPage(); }

    public function arsip($id)
    {
        $bankSoal = BankSoal::findOrFail($id);
        Gate::authorize('update', $bankSoal);

        $bankSoal->update(['status' => 'arsip']);
        session()->flash('sukses', 'Soal berhasil diarsipkan.');
    }

    public function hapusPermanen($id)
    {
        $bankSoal = BankSoal::findOrFail($id);
        Gate::authorize('delete', $bankSoal);

        // Cek referensi
        if ($bankSoal->soalUjian()->exists() || $bankSoal->anggotaPaketSoal()->exists()) {
            session()->flash('error', 'Gagal hapus: Soal sudah digunakan dalam ujian atau paket soal.');
            return;
        }

        // Hapus gambar terkait sebelum menghapus record
        if ($bankSoal->gambar_soal) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($bankSoal->gambar_soal);
        }
        foreach ($bankSoal->pilihanJawabans as $pilihan) {
            if ($pilihan->gambar_jawaban) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($pilihan->gambar_jawaban);
            }
        }

        $bankSoal->forceDelete(); // Hapus permanen
        session()->flash('sukses', 'Soal berhasil dihapus permanen.');
    }

    public function render()
    {
        Gate::authorize('viewAny', BankSoal::class);

        $query = BankSoal::with(['mataPelajaran', 'guru']);

        // Isolasi data untuk guru
        if (auth()->user()->peran === 'guru') {
            $query->where('guru_id', auth()->id());
        }

        if ($this->search) {
            $query->where('teks_soal', 'like', '%' . $this->search . '%');
        }
        if ($this->mapel_id) {
            $query->where('mata_pelajaran_id', $this->mapel_id);
        }
        if ($this->jenis_soal) {
            $query->where('jenis_soal', $this->jenis_soal);
        }
        if ($this->status) {
            $query->where('status', $this->status);
        }

        $bankSoals = $query->orderBy('created_at', 'desc')->paginate(10);
        $mapels = MataPelajaran::orderBy('nama_mapel')->get();

        return view('livewire.bank-soal-manager', [
            'bankSoals' => $bankSoals,
            'mapels' => $mapels
        ]);
    }
}