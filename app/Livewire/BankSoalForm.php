<?php

namespace App\Livewire;

use App\Models\BankSoal;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.livewire')]
class BankSoalForm extends Component
{
    use WithFileUploads;

    public $bankSoalId;
    public $mata_pelajaran_id = '';
    public $jenis_soal = 'pg';
    public $teks_soal = '';
    public $gambar_soal;
    public $existing_gambar_soal = '';
    public $tingkat_kesukaran = 'sedang';
    public $bobot_nilai = 1.00;
    public $status = 'draft';
    public $rubrik_esai = '';

    public $pilihan = [];

    public function mount($id = null)
    {
        Gate::authorize('create', BankSoal::class);

        if ($id) {
            $this->bankSoalId = $id;
            $bankSoal = BankSoal::findOrFail($id);
            Gate::authorize('update', $bankSoal);

            $this->mata_pelajaran_id = $bankSoal->mata_pelajaran_id;
            $this->jenis_soal = $bankSoal->jenis_soal;
            $this->teks_soal = $bankSoal->teks_soal;
            $this->existing_gambar_soal = $bankSoal->gambar_soal;
            $this->tingkat_kesukaran = $bankSoal->tingkat_kesukaran ?? 'sedang';
            $this->bobot_nilai = $bankSoal->bobot_nilai ?? 1.00;
            $this->status = $bankSoal->status;
            $this->rubrik_esai = $bankSoal->rubrik_esai ?? '';

            $this->pilihan = $bankSoal->pilihanJawabans->map(function ($p) {
                return [
                    'label' => $p->label,
                    'teks_jawaban' => $p->teks_jawaban,
                    'gambar_jawaban' => $p->gambar_jawaban,
                    'is_benar' => $p->is_benar,
                    'existing_gambar' => $p->gambar_jawaban,
                    'new_gambar' => null
                ];
            })->toArray();
        } else {
            $this->initPilihan();
        }
    }

    public function updatedJenisSoal()
    {
        $this->initPilihan();
    }

    private function initPilihan()
    {
        $this->pilihan = [];
        if ($this->jenis_soal === 'pg') {
            foreach (['A', 'B', 'C', 'D', 'E'] as $label) {
                $this->pilihan[] = ['label' => $label, 'teks_jawaban' => '', 'is_benar' => false, 'new_gambar' => null];
            }
        } elseif ($this->jenis_soal === 'benar_salah') {
            $this->pilihan[] = ['label' => 'Benar', 'teks_jawaban' => 'Benar', 'is_benar' => false, 'new_gambar' => null];
            $this->pilihan[] = ['label' => 'Salah', 'teks_jawaban' => 'Salah', 'is_benar' => false, 'new_gambar' => null];
        } elseif ($this->jenis_soal === 'pg_kompleks') {
            $this->pilihan[] = ['label' => '1', 'teks_jawaban' => '', 'is_benar' => false, 'new_gambar' => null];
            $this->pilihan[] = ['label' => '2', 'teks_jawaban' => '', 'is_benar' => false, 'new_gambar' => null];
        }
    }

    public function addPilihan()
    {
        $count = count($this->pilihan) + 1;
        $this->pilihan[] = ['label' => (string)$count, 'teks_jawaban' => '', 'is_benar' => false, 'new_gambar' => null];
    }

    public function removePilihan($index)
    {
        if (count($this->pilihan) > 2) {
            array_splice($this->pilihan, $index, 1);
        }
    }

    protected function rules()
    {
        $rules = [
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'jenis_soal' => 'required|in:pg,pg_kompleks,benar_salah,esai',
            'teks_soal' => 'required|string|max:5000',
            'gambar_soal' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'tingkat_kesukaran' => 'required|in:mudah,sedang,sulit',
            'bobot_nilai' => 'required|numeric|min:0.01|max:100',
            'status' => 'required|in:draft,aktif,arsip',
        ];

        if ($this->jenis_soal === 'esai') {
            $rules['rubrik_esai'] = 'required|string|max:5000';
        } else {
            $rules['pilihan'] = 'required|array|min:2';
            $rules['pilihan.*.teks_jawaban'] = 'required|string|max:1000';
            $rules['pilihan.*.new_gambar'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
        }

        return $rules;
    }

    public function save()
    {
        $this->validate();

        // Validasi bisnis khusus jenis soal
        $benarCount = collect($this->pilihan)->where('is_benar', true)->count();
        if ($this->jenis_soal === 'pg' && $benarCount !== 1) {
            $this->addError('pilihan', 'Pilihan Ganda harus memiliki tepat 1 jawaban benar.');
            return;
        }
        if ($this->jenis_soal === 'pg_kompleks' && $benarCount < 1) {
            $this->addError('pilihan', 'Pilihan Ganda Kompleks harus memiliki minimal 1 jawaban benar.');
            return;
        }
        if ($this->jenis_soal === 'benar_salah' && $benarCount !== 1) {
            $this->addError('pilihan', 'Benar/Salah harus memiliki tepat 1 jawaban benar.');
            return;
        }

        // Bangun kunci jawaban di server (tidak dipercaya dari browser)
        $kunciJawaban = collect($this->pilihan)->where('is_benar', true)->pluck('label')->values()->toArray();

        // guru_id ditetapkan oleh server, bukan dari input pengguna
        $guruId = auth()->id();

        // Data TANPA guru_id (karena guru_id tidak fillable demi keamanan)
        $data = [
            'mata_pelajaran_id' => $this->mata_pelajaran_id,
            'jenis_soal' => $this->jenis_soal,
            'teks_soal' => $this->teks_soal,
            'kunci_jawaban' => $kunciJawaban,
            'rubrik_esai' => $this->jenis_soal === 'esai' ? $this->rubrik_esai : null,
            'bobot_nilai' => $this->bobot_nilai,
            'tingkat_kesukaran' => $this->tingkat_kesukaran,
            'status' => $this->status,
        ];

        DB::beginTransaction();
        $newGambarSoalPath = null;
        try {
            if ($this->gambar_soal) {
                $newGambarSoalPath = $this->gambar_soal->store('soal/guru_' . $guruId, 'public');
            }

            // gambar_soal: NULL jika kosong, bukan string kosong
            $data['gambar_soal'] = $newGambarSoalPath ?: ($this->existing_gambar_soal ?: null);

            if ($this->bankSoalId) {
                // MODE EDIT: otorisasi ulang di server (temuan audit)
                $bankSoal = BankSoal::findOrFail($this->bankSoalId);
                Gate::authorize('update', $bankSoal);

                $bankSoal->update($data);
                $oldGambar = $this->existing_gambar_soal;

                // Hapus gambar soal lama hanya setelah DB berhasil di-update
                if ($newGambarSoalPath && $oldGambar && $newGambarSoalPath !== $oldGambar) {
                    Storage::disk('public')->delete($oldGambar);
                }

                // Cleanup gambar pilihan yang tidak lagi dikirim (cegah orphan file)
                $submittedExisting = collect($this->pilihan)->pluck('existing_gambar')->filter()->values()->toArray();
                foreach ($bankSoal->pilihanJawabans as $oldPilihan) {
                    if ($oldPilihan->gambar_jawaban && !in_array($oldPilihan->gambar_jawaban, $submittedExisting, true)) {
                        Storage::disk('public')->delete($oldPilihan->gambar_jawaban);
                    }
                }

                // Hapus pilihan lama, buat baru
                $bankSoal->pilihanJawabans()->delete();
                $urutan = 1;
                foreach ($this->pilihan as $p) {
                    $newPilihanGambar = null;
                    if (isset($p['new_gambar']) && $p['new_gambar']) {
                        $newPilihanGambar = $p['new_gambar']->store('soal/guru_' . $guruId . '/pilihan', 'public');
                    }

                    $bankSoal->pilihanJawabans()->create([
                        'label' => $p['label'],
                        'teks_jawaban' => $p['teks_jawaban'],
                        'gambar_jawaban' => $newPilihanGambar ?: ($p['existing_gambar'] ?? null),
                        'is_benar' => (bool)$p['is_benar'],
                        'urutan' => $urutan++
                    ]);

                    // Cleanup gambar pilihan lama jika diganti dengan yang baru
                    if ($newPilihanGambar && isset($p['existing_gambar']) && $p['existing_gambar'] && $newPilihanGambar !== $p['existing_gambar']) {
                        Storage::disk('public')->delete($p['existing_gambar']);
                    }
                }
                $message = 'Soal berhasil diperbarui.';
            } else {
                // MODE TAMBAH: guru_id di-set eksplisit di luar mass assignment
                Gate::authorize('create', BankSoal::class);

                $bankSoal = new BankSoal($data);
                $bankSoal->guru_id = $guruId;
                $bankSoal->save();

                $urutan = 1;
                foreach ($this->pilihan as $p) {
                    $newPilihanGambar = null;
                    if (isset($p['new_gambar']) && $p['new_gambar']) {
                        $newPilihanGambar = $p['new_gambar']->store('soal/guru_' . $guruId . '/pilihan', 'public');
                    }

                    $bankSoal->pilihanJawabans()->create([
                        'label' => $p['label'],
                        'teks_jawaban' => $p['teks_jawaban'],
                        'gambar_jawaban' => $newPilihanGambar,
                        'is_benar' => (bool)$p['is_benar'],
                        'urutan' => $urutan++
                    ]);
                }
                $message = 'Soal berhasil dibuat.';
            }

            DB::commit();
            
            // Reset file upload agar tidak tersimpan di state Livewire
            $this->gambar_soal = null;
            foreach ($this->pilihan as $index => $p) {
                if (isset($this->pilihan[$index]['new_gambar'])) {
                    $this->pilihan[$index]['new_gambar'] = null;
                }
            }
            
            session()->flash('sukses', $message);
            return redirect()->route('bank-soal.index');

        } catch (\Exception $e) {
            DB::rollBack();
            // Cleanup gambar baru jika transaksi gagal (file lama tetap aman)
            if ($newGambarSoalPath) {
                Storage::disk('public')->delete($newGambarSoalPath);
            }
            $this->addError('save', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $mapels = MataPelajaran::orderBy('nama_mapel')->get();
        return view('livewire.bank-soal-form', ['mapels' => $mapels]);
    }
}