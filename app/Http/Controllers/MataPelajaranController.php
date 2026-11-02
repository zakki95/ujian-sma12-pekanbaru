<?php
namespace App\Http\Controllers;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    public function index() { $this->authorize('viewAny', MataPelajaran::class); return view('mapel.index', ['mapel' => MataPelajaran::paginate(10)]); }
    public function show(MataPelajaran $mata_pelajaran) { $this->authorize('view', $mata_pelajaran); return view('mapel.show', compact('mata_pelajaran')); }
}