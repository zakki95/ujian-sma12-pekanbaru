<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportSiswaRequest;
use App\Imports\ImportSiswa;
use App\Imports\ImportSiswaScan;
use App\Exports\TemplateSiswaExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Exceptions\SheetNotFoundException;
use PhpOffice\PhpSpreadsheet\Exception;

class ImportSiswaController extends Controller
{
    public function showForm()
    {
        return view('import.siswa-form');
    }

    public function preview(ImportSiswaRequest $request)
    {
        $path = $request->file('file_siswa')->store('temp');

        try {
            // Tahap 1: Pre-scan untuk mendeteksi duplikat intra-file
            $scan = new ImportSiswaScan();
            Excel::import($scan, storage_path('app/' . $path));

            $scannedRows = $scan->dataSheet->scannedRows;
            $duplicateNis = collect($scannedRows)
                ->pluck('nomor_induk')
                ->duplicates()
                ->unique()
                ->values()
                ->toArray();

            // Tahap 2: Import utama dengan mode preview
            $import = new ImportSiswa();
            $import->previewMode = true;
            $import->duplicateNis = $duplicateNis;
            Excel::import($import, storage_path('app/' . $path));

            return view('import.siswa-preview', [
                'validRows' => $import->validRows,
                'importErrors' => $import->allErrors,
                'filePath' => $path,
            ]);
        } catch (SheetNotFoundException $e) {
            Storage::delete($path);
            return back()->withErrors([
                'file_siswa' => 'Worksheet "Data" tidak ditemukan. Silakan gunakan template resmi.',
            ]);
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            // Tangani error "Start row beyond highest row" (file hanya header, tanpa data)
            if (str_contains($e->getMessage(), 'beyond highest row')) {
                Storage::delete($path);
                return back()->withErrors([
                    'file_siswa' => 'File Excel tidak memiliki data siswa (hanya header). Silakan isi minimal satu baris data.',
                ]);
            }
            // Exception PhpSpreadsheet lain - lempar ke Laravel handler
            throw $e;
        } catch (\Exception $e) {
            // Fallback untuk exception lain yang tidak terduga
            Storage::delete($path);
            return back()->withErrors([
                'file_siswa' => 'Terjadi kesalahan saat memproses file: ' . $e->getMessage(),
            ]);
        }
    }

    public function process(Request $request)
    {
        $request->validate(['file_path' => 'required|string']);
        $path = $request->file_path;

        try {
            // Tahap 1: Pre-scan ulang (file yang sama)
            $scan = new ImportSiswaScan();
            Excel::import($scan, storage_path('app/' . $path));

            $scannedRows = $scan->dataSheet->scannedRows;
            $duplicateNis = collect($scannedRows)
                ->pluck('nomor_induk')
                ->duplicates()
                ->unique()
                ->values()
                ->toArray();

            // Tahap 2: Import utama dengan mode process
            $import = new ImportSiswa();
            $import->previewMode = false;
            $import->duplicateNis = $duplicateNis;
            Excel::import($import, storage_path('app/' . $path));

            // Hapus file sementara
            Storage::delete($path);

            return redirect()->route('kelas.index')->with('sukses', 'Data siswa berhasil diimpor.');
        } catch (SheetNotFoundException $e) {
            return back()->withErrors([
                'file_path' => 'Worksheet "Data" tidak ditemukan.',
            ]);
        } catch (PhpSpreadsheetException $e) {
            if (str_contains($e->getMessage(), 'beyond highest row')) {
                Storage::delete($path);
                return back()->withErrors([
                    'file_path' => 'File Excel tidak memiliki data siswa (hanya header).',
                ]);
            }
            throw $e;
        }
    }

    public function downloadTemplate()
    {
        return Excel::download(new TemplateSiswaExport(), 'template_import_siswa.xlsx');
    }
}