<?php
namespace App\Console\Commands;
use App\Models\Pengguna;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetPasswordCommand extends Command
{
    protected $signature = 'app:reset-password {email}';
    protected $description = 'Reset password pengguna secara aman (input tersembunyi)';

    public function handle(): int
    {
        $user = Pengguna::where('email', $this->argument('email'))->first();
        if (!$user) {
            $this->error('Pengguna dengan email tersebut tidak ditemukan.');
            return self::FAILURE;
        }
        $baru = $this->secret('Password baru (minimal 8 karakter)');
        if (strlen($baru) < 8) {
            $this->error('Password minimal 8 karakter.');
            return self::FAILURE;
        }
        $user->update(['kata_sandi' => Hash::make($baru)]);
        $this->info('Password untuk ' . $user->email . ' berhasil direset.');
        return self::SUCCESS;
    }
}