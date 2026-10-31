<?php

namespace Database\Seeders;

use App\Models\{Pengguna, Kelas, MataPelajaran, GuruMapel, AnggotaKelas, BankSoal, PilihanJawaban, PaketSoal, AnggotaPaketSoal, Ujian, JadwalUjian, PesertaUjian, TokenUjian, SesiUjian, SoalUjian, JawabanSiswa, HasilUjian, RincianNilai};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET session_replication_role = replica;'); // Nonaktifkan sementara FK check untuk kecepatan seeding

        echo "🌱 Memulai seeding data uji yang aman...\n";

        // 1. Pengguna
        $admin = Pengguna::factory()->create(['peran' => 'admin', 'nama_lengkap' => 'Administrator Sekolah', 'email' => 'admin@sekolah.test']);
        $guruList = Pengguna::factory()->count(3)->create(['peran' => 'guru']);
        $siswaList = Pengguna::factory()->count(50)->create(['peran' => 'siswa']);
        echo "✅ 1 Admin, 3 Guru, 50 Siswa dibuat.\n";

        // 2. Kelas & Anggota
        $kelasList = Kelas::factory()->count(3)->create();
        foreach ($kelasList as $kelas) {
            $kelasSiswa = $siswaList->random(15);
            foreach ($kelasSiswa as $siswa) {
                AnggotaKelas::create(['kelas_id' => $kelas->id, 'pengguna_id' => $siswa->id, 'status' => 'aktif']);
            }
        }
        echo "✅ 3 Kelas dan keanggotaan siswa dibuat.\n";

        // 3. Mata Pelajaran & Guru Mapel
        $mapelList = MataPelajaran::factory()->count(2)->create();
        foreach ($mapelList as $index => $mapel) {
            GuruMapel::create(['pengguna_id' => $guruList[$index]->id, 'mata_pelajaran_id' => $mapel->id]);
        }
        echo "✅ 2 Mata Pelajaran dan penugasan guru dibuat.\n";

        // 4. Bank Soal & Pilihan Jawaban
        $bankSoalList = collect();
        foreach ($mapelList as $mapel) {
            $guru = $mapel->guruMapel->first()->pengguna;
            for ($i = 0; $i < 5; $i++) {
                $soal = BankSoal::factory()->create([
                    'guru_id' => $guru->id,
                    'mata_pelajaran_id' => $mapel->id,
                ]);
                $bankSoalList->push($soal);
                
                // Buat 4 pilihan jawaban, 1 benar
                $labels = ['A', 'B', 'C', 'D'];
                $benarIdx = array_rand($labels);
                foreach ($labels as $idx => $label) {
                    PilihanJawaban::factory()->create([
                        'bank_soal_id' => $soal->id,
                        'label' => $label,
                        'is_benar' => ($idx === $benarIdx),
                        'urutan' => $idx + 1,
                    ]);
                }
            }
        }
        echo "✅ 10 Bank Soal beserta 40 Pilihan Jawaban dibuat.\n";

        // 5. Paket Soal
        $paket = PaketSoal::factory()->create([
            'guru_id' => $guruList[0]->id,
            'mata_pelajaran_id' => $mapelList[0]->id,
            'nama_paket' => 'Paket Ujian Matematika Dasar',
        ]);
        foreach ($bankSoalList->where('mata_pelajaran_id', $mapelList[0]->id)->take(5) as $idx => $soal) {
            AnggotaPaketSoal::create(['paket_soal_id' => $paket->id, 'bank_soal_id' => $soal->id, 'urutan' => $idx + 1]);
        }
        echo "✅ 1 Paket Soal dibuat.\n";

        // 6. Ujian & Jadwal
        $ujian = Ujian::factory()->create();
        $jadwal = JadwalUjian::factory()->create([
            'ujian_id' => $ujian->id,
            'mata_pelajaran_id' => $mapelList[0]->id,
            'paket_soal_id' => $paket->id,
        ]);
        echo "✅ 1 Ujian dan Jadwal dibuat.\n";

        // 7. Peserta Ujian (Siswa dari kelas X IPA 1)
        $kelasTarget = $kelasList[0];
        $pesertaList = collect();
        foreach ($kelasTarget->anggotaKelas as $anggota) {
            $peserta = PesertaUjian::factory()->create([
                'jadwal_ujian_id' => $jadwal->id,
                'pengguna_id' => $anggota->pengguna_id,
            ]);
            $pesertaList->push($peserta);
        }
        echo "✅ " . $pesertaList->count() . " Peserta Ujian terdaftar.\n";

        // 8. Token Ujian
        $token = TokenUjian::factory()->create(['jadwal_ujian_id' => $jadwal->id]);
        echo "✅ 1 Token Ujian dibuat.\n";

        // 9. Sesi Ujian & Jawaban (Simulasi 5 siswa sedang mengerjakan)
        $siswaMengerjakan = $pesertaList->take(5);
        foreach ($siswaMengerjakan as $peserta) {
            $sesi = SesiUjian::factory()->create([
                'peserta_ujian_id' => $peserta->id,
                'token_ujian_id' => $token->id,
                'status' => 'aktif',
            ]);
            
            // Buat snapshot soal untuk sesi ini
            $soalUjianList = collect();
            foreach ($paket->anggotaPaketSoal()->orderBy('urutan')->get() as $idx => $anggotaPaket) {
                $soalUjian = SoalUjian::factory()->create([
                    'sesi_ujian_id' => $sesi->id,
                    'bank_soal_id' => $anggotaPaket->bank_soal_id,
                    'urutan' => $idx + 1,
                ]);
                $soalUjianList->push($soalUjian);
            }

            // Buat jawaban untuk setiap soal
            foreach ($soalUjianList as $soalUjian) {
                JawabanSiswa::factory()->create([
                    'sesi_ujian_id' => $sesi->id,
                    'soal_ujian_id' => $soalUjian->id,
                ]);
            }
        }
        echo "✅ 5 Sesi Ujian aktif beserta Soal dan Jawaban dibuat.\n";

        // 10. Hasil Ujian (untuk 2 siswa yang sudah selesai)
        $siswaSelesai = $siswaMengerjakan->take(2);
        foreach ($siswaSelesai as $peserta) {
            $hasil = HasilUjian::factory()->create(['peserta_ujian_id' => $peserta->id]);
            RincianNilai::factory()->create(['hasil_ujian_id' => $hasil->id]);
        }
        echo "✅ 2 Hasil Ujian dan Rincian Nilai dibuat.\n";

        DB::statement('SET session_replication_role = DEFAULT;'); // Aktifkan kembali FK check
        echo "\n🎉 SEEDING SELESAI! Database siap untuk pengujian.\n";
        echo "💡 Info Login Uji: Email = admin@sekolah.test (atau email guru/siswa di tabel pengguna), Password = password123\n";
    }
}