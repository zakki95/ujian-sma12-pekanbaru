<?php
namespace App\Http\Controllers;
use App\Http\Requests\ImportSiswaRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportSiswa;

class ImportSiswaController extends Controller
{
    public function showForm() { return view('import.siswa-form'); }

    public function preview(ImportSiswaRequest $request)
    {
        $path = $request->file('file_siswa')->store('temp');
        $import = new ImportSiswa();
        $import->onlyPreview();
        Excel::import($import, storage_path('app/' . $path));

        return view('import.siswa-preview', [
            'validRows' => $import->getValidRows(),
            'importErrors' => $import->getErrors(),
            'filePath' => $path,
        ]);
    }

    public function process(Request $request)
    {
        $request->validate(['file_path' => 'required|string']);
        $import = new ImportSiswa();
        Excel::import($import, storage_path('app/' . $request->file_path));
        \Storage::delete($request->file_path);
        return redirect()->route('kelas.index')->with('sukses', 'Data siswa berhasil diimpor.');
    }
}