<?php

namespace App\Console\Commands;

use App\Models\Pengguna;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    protected $signature = 'app:create-admin';
    protected $description = 'Membuat akun admin baru dengan aman (password tidak terlihat di terminal)';

    public function handle(): int
    {
        $this->info('Membuat Akun Admin Baru');
        $this->info('========================');

        $nama = $this->ask('Nama lengkap admin');
        $email = $this->ask('Email admin');

        if (Pengguna::where('email', $email)->exists()) {
            $this->error("Email {$email} sudah terdaftar!");
            return self::FAILURE;
        }

        $password = $this->secret('Password admin (tidak akan terlihat)');
        $passwordConfirm = $this->secret('Konfirmasi password');

        if ($password !== $passwordConfirm) {
            $this->error('Password tidak cocok!');
            return self::FAILURE;
        }

        if (strlen($password) < 8) {
            $this->error('Password minimal 8 karakter!');
            return self::FAILURE;
        }

        $admin = Pengguna::create([
            'nama_lengkap' => $nama,
            'email' => $email,
            'kata_sandi' => Hash::make($password),
            'peran' => 'admin',
            'status' => 'aktif',
        ]);

        $this->info('Admin berhasil dibuat!');
        $this->info('   Nama: ' . $admin->nama_lengkap);
        $this->info('   Email: ' . $admin->email);
        $this->info('   Peran: ' . $admin->peran);
        $this->info('   Status: ' . $admin->status);
        $this->warn('Silakan login dan ganti password segera!');

        return self::SUCCESS;
    }
}