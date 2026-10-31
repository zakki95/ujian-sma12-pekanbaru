<?php
$base = __DIR__ . '\\app\\Models';

$models = [
    'Pengguna' => 'pengguna',
    'Kelas' => 'kelas',
    'MataPelajaran' => 'mata_pelajaran',
    'GuruMapel' => 'guru_mapel',
    'AnggotaKelas' => 'anggota_kelas',
    'BankSoal' => 'bank_soal',
    'PilihanJawaban' => 'pilihan_jawaban',
    'BerkasSoal' => 'berkas_soal',
    'PaketSoal' => 'paket_soal',
    'AnggotaPaketSoal' => 'anggota_paket_soal',
    'Ujian' => 'ujian',
    'JadwalUjian' => 'jadwal_ujian',
    'PengawasJadwal' => 'pengawas_jadwal',
    'PesertaUjian' => 'peserta_ujian',
    'SesiUjian' => 'sesi_ujian',
    'SoalUjian' => 'soal_ujian',
    'JawabanSiswa' => 'jawaban_siswa',
    'TokenUjian' => 'token_ujian',
    'LogPelanggaran' => 'log_pelanggaran',
    'NotifikasiPengawas' => 'notifikasi_pengawas',
    'HasilUjian' => 'hasil_ujian',
    'RincianNilai' => 'rincian_nilai',
    'AuditLog' => 'audit_log',
];

foreach ($models as $model => $table) {
    $file = $base . "\\{$model}.php";
    if (!file_exists($file)) {
        echo "⚠️  {$model}.php tidak ditemukan\n";
        continue;
    }
    
    $content = file_get_contents($file);
    
    // Cek apakah sudah ada $table property
    if (strpos($content, "protected \$table") !== false) {
        // Update existing
        $content = preg_replace("/protected \\\$table = '[^']+'/", "protected \$table = '{$table}'", $content);
    } else {
        // Add new $table property after class declaration
        $content = preg_replace(
            "/(class {$model} extends Model\s*\n\{)/",
            "class {$model} extends Model\n{\n    protected \$table = '{$table}';",
            $content
        );
    }
    
    file_put_contents($file, $content);
    echo "✅ {$model} -> tabel '{$table}'\n";
}

echo "\n SELESAI! Semua model sudah diperbaiki.\n";