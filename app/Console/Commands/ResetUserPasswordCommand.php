<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetUserPasswordCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:reset-password {email? : Alamat email pengguna/admin yang ingin direset}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset password akun pengguna atau Super Admin secara langsung melalui terminal CLI';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');

        if (! $email) {
            $email = $this->ask('Masukkan alamat email pengguna/admin');
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("Pengguna dengan email '{$email}' tidak ditemukan dalam database.");
            return self::FAILURE;
        }

        $roleName = $user->role?->name ?? 'Tanpa Role';
        $this->info("Pengguna ditemukan: {$user->name} | Email: {$user->email} | Role: {$roleName}");

        $newPassword = $this->secret('Masukkan password baru (minimal 8 karakter)');

        if (strlen((string) $newPassword) < 8) {
            $this->error('Gagal: Password baru harus memiliki panjang minimal 8 karakter.');
            return self::FAILURE;
        }

        $confirmPassword = $this->secret('Konfirmasi password baru');

        if ($newPassword !== $confirmPassword) {
            $this->error('Gagal: Konfirmasi password tidak cocok.');
            return self::FAILURE;
        }

        $user->forceFill([
            'password' => Hash::make($newPassword),
        ])->save();

        AuditLog::log('password_reset_cli', "Password akun {$user->name} ({$user->email}) direset via Artisan CLI", $user);

        $this->info("✓ Berhasil! Password untuk akun {$user->email} telah berhasil diperbarui.");
        return self::SUCCESS;
    }
}
