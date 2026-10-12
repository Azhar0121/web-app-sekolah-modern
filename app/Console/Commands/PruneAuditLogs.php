<?php

namespace App\Console\Commands;

use App\Services\AuditLogRetentionService;
use Illuminate\Console\Command;

class PruneAuditLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audit-logs:prune {--years=3 : Batas retensi usia log dalam tahun}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bersihkan riwayat audit log yang melebihi batas retensi (default 3 tahun) dan kirim notifikasi ke admin';

    public function handle(AuditLogRetentionService $service): int
    {
        $years = (int) $this->option('years');
        if ($years < 1) {
            $years = 3;
        }

        $this->info("Memeriksa riwayat audit log yang berusia lebih dari {$years} tahun...");

        $deletedCount = $service->pruneExpiredLogs($years, 'otomatis via Scheduler');

        if ($deletedCount > 0) {
            $this->info("Sukses: {$deletedCount} data audit log kedaluwarsa berhasil dibersihkan.");
            $this->info("Notifikasi database telah dikirimkan ke Super Admin dan Kepala Sekolah.");
        } else {
            $this->line("Tidak ada data audit log yang melebihi batas retensi {$years} tahun.");
        }

        return Command::SUCCESS;
    }
}