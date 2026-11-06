<?php

namespace App\Livewire;

use App\Models\PaketSoal;
use App\Models\MataPelajaran;
use App\Models\BankSoal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.livewire')]
class PaketSoalForm extends Component
{
    public $paketSoalId;
    public $nama_paket = '';
    public $mata_pelajaran_id = '';
    public $deskripsi = '';
    public $durasi_menit = 60;
    public $status = 'draft';

    public $selectedBankSoalIds = [];
    public $searchSoal = '';

    public function mount($id = null)
    {
        Gate::authorize('create', PaketSoal::class);

        if ($id) {
            $this->paketSoalId = $id;
            $paket = PaketSoal::findOrFail($id);
            Gate::authorize('update', $paket);

            $this->nama_paket = $paket->nama_paket;
            $this->mata_pelajaran_id = $paket->mata_pelajaran_id;
            $this->deskripsi = $paket->deskripsi ?? '';
            $this->durasi_menit = $paket->durasi_menit;
            $this->status = $paket->status;

            $this->selectedBankSoalIds = $paket->anggotaPaketSoal()
                ->orderBy('urutan')
                ->pluck('bank_soal_id')
                ->toArray();
        }
    }

    public function addSoal($bankSoalId)
    {
        if (!in_array($bankSoalId, $this->selectedBankSoalIds)) {
            $this->selectedBankSoalIds[] = $bankSoalId;
        }
    }

    public function removeSoal($index)
    {
        if (isset($this->selectedBankSoalIds[$index])) {
            array_splice($this->selectedBankSoalIds, $index, 1);
        }
    }

    public function moveUp($index)
    {
        $ids = array_values($this->selectedBankSoalIds); // Paksa indeks berurutan 0,1,2...
        if ($index > 0 && isset($ids[$index]) && isset($ids[$index - 1])) {
            $temp = $ids[$index];
            $ids[$index] = $ids[$index - 1];
            $ids[$index - 1] = $temp;
            $this->selectedBankSoalIds = $ids; // Re-assign ke properti Livewire
        }
    }

    public function moveDown($index)
    {
        $ids = array_values($this->selectedBankSoalIds); // Paksa indeks berurutan 0,1,2...
        if ($index < count($ids) - 1 && isset($ids[$index]) && isset($ids[$index + 1])) {
            $temp = $ids[$index];
            $ids[$index] = $ids[$index + 1];
            $ids[$index + 1] = $temp;
            $this->selectedBankSoalIds = $ids; // Re-assign ke properti Livewire
        }
    }

    protected function rules()
    {
        return [
            'nama_paket' => 'required|string|max:255',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'deskripsi' => 'nullable|string|max:1000',
            'durasi_menit' => 'required|integer|min:1|max:300',
            'status' => 'required|in:draft,aktif,arsip',
        ];
    }

    public function save()
    {
        $this->validate();

        if ($this->paketSoalId) {
            $paket = PaketSoal::findOrFail($this->paketSoalId);
            Gate::authorize('update', $paket);

            if ($paket->jadwalUjian()->exists()) {
                $this->addError('save', 'Paket soal ini tidak dapat diubah strukturnya karena sudah digunakan dalam jadwal ujian.');
                return;
            }
        } else {
            Gate::authorize('create', PaketSoal::class);
        }

        $guruId = auth()->id();
        $isAdmin = auth()->user()->peran === 'admin';
        $validIds = collect($this->selectedBankSoalIds)->filter()->unique()->values();

        // Validasi ketat di server
        $questions = BankSoal::whereIn('id', $validIds)->get();
        foreach ($questions as $q) {
            if (!$isAdmin && $q->guru_id !== $guruId) {
                $this->addError('save', 'Anda tidak memiliki hak untuk menambahkan soal milik guru lain.');
                return;
            }
            if ($q->mata_pelajaran_id !== $this->mata_pelajaran_id) {
                $this->addError('save', 'Mata pelajaran soal harus sama dengan mata pelajaran paket.');
                return;
            }
            if ($q->status !== 'aktif') {
                $this->addError('save', 'Hanya soal berstatus aktif yang dapat ditambahkan ke paket.');
                return;
            }
        }

        DB::beginTransaction();
        try {
            $data = [
                'nama_paket' => $this->nama_paket,
                'mata_pelajaran_id' => $this->mata_pelajaran_id,
                'deskripsi' => $this->deskripsi,
                'durasi_menit' => $this->durasi_menit,
                'status' => $this->status,
            ];

            if ($this->paketSoalId) {
                $paket->update($data);
                $paket->anggotaPaketSoal()->delete();
            } else {
                $data['guru_id'] = $guruId;
                $paket = PaketSoal::create($data);
            }

            $urutan = 1;
            foreach ($validIds as $bankSoalId) {
                $paket->anggotaPaketSoal()->create([
                    'bank_soal_id' => $bankSoalId,
                    'urutan' => $urutan++
                ]);
            }

            DB::commit();
            session()->flash('sukses', 'Paket soal berhasil disimpan.');
            return redirect()->route('paket-soal.index');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('save', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $mapels = MataPelajaran::orderBy('nama_mapel')->get();
        
        $availableQuestions = collect();
        if ($this->mata_pelajaran_id) {
            $query = BankSoal::where('mata_pelajaran_id', $this->mata_pelajaran_id)
                             ->where('status', 'aktif');
            
            if (auth()->user()->peran === 'guru') {
                $query->where('guru_id', auth()->id());
            }

            if ($this->searchSoal) {
                $query->where('teks_soal', 'like', '%' . $this->searchSoal . '%');
            }

            $availableQuestions = $query->limit(50)->get(); // Batasi untuk performa UI
        }

        $selectedQuestionsData = BankSoal::whereIn('id', $this->selectedBankSoalIds)->get()->keyBy('id');
        // Urutkan sesuai selectedBankSoalIds
        $orderedSelected = collect($this->selectedBankSoalIds)->map(fn($id) => $selectedQuestionsData->get($id))->filter()->values();

        return view('livewire.paket-soal-form', [
            'mapels' => $mapels,
            'availableQuestions' => $availableQuestions,
            'orderedSelected' => $orderedSelected
        ]);
    }
}