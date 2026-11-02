<?php
namespace App\Http\Controllers;
use App\Models\Kelas;
use App\Http\Requests\StoreKelasRequest;
use App\Http\Requests\UpdateKelasRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Kelas::class);
        $query = Kelas::with('waliKelas');
        if ($request->filled('search')) {
            $query->where('nama_kelas', 'like', '%' . $request->search . '%');
        }
        $kelas = $query->orderBy('nama_kelas')->paginate(10);
        return view('kelas.index', compact('kelas'));
    }

    public function store(StoreKelasRequest $request)
    {
        Kelas::create($request->validated());
        return redirect()->route('kelas.index')->with('sukses', 'Kelas dibuat.');
    }

    public function update(UpdateKelasRequest $request, $id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->update($request->validated());
        return redirect()->route('kelas.index')->with('sukses', 'Kelas diperbarui.');
    }

    public function destroy($id)
    {
        // Explicit finding: Menghindari masalah Route Model Binding di environment testing
        $kelas = Kelas::findOrFail($id);
        
        $this->authorize('delete', $kelas);

        // Cek relasi menggunakan Query Builder untuk akurasi mutlak
        $adaSiswaAktif = DB::table('anggota_kelas')
            ->where('kelas_id', $kelas->id)
            ->where('status', 'aktif')
            ->exists();

        if ($adaSiswaAktif) {
            return redirect()->route('kelas.index')->with('error_delete', 'Gagal hapus: Masih ada siswa aktif di kelas ini.');
        }

        $kelas->delete();
        return redirect()->route('kelas.index')->with('sukses', 'Kelas dihapus.');
    }
}