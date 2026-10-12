<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\AuditLogPrunedNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class AuditLogRetentionService
{
    public function getCutoffDate(int $years = 3): Carbon
    {
        return now()->subYears($years);
    }

    public function getExpiredLogsCount(int $years = 3): int
    {
        return AuditLog::where('created_at', '<', $this->getCutoffDate($years))->count();
    }

    public function pruneExpiredLogs(int $years = 3, string $triggeredBy = 'otomatis'): int
    {
        $cutoffDate = $this->getCutoffDate($years);
        $count = AuditLog::where('created_at', '<', $cutoffDate)->count();

        if ($count > 0) {
            AuditLog::where('created_at', '<', $cutoffDate)->delete();

            $cutoffFormatted = $cutoffDate->translatedFormat('d F Y');
            $user = auth()->user();

            AuditLog::create([
                'user_id'        => $user?->id,
                'user_name'      => $user?->name ?? 'Sistem Otomatis',
                'event'          => 'delete',
                'auditable_type' => AuditLog::class,
                'auditable_id'   => null,
                'description'    => "Pembersihan riwayat audit log: {$count} data log berusia lebih dari {$years} tahun (sebelum {$cutoffFormatted}) telah dibersihkan ({$triggeredBy}).",
                'ip_address'     => request()->ip() ?? '127.0.0.1',
                'user_agent'     => request()->userAgent() ?? 'System / Scheduler',
            ]);

            // Kirim notifikasi database ke Super Admin dan Kepala Sekolah
            $recipients = User::whereHas('role', function ($q) {
                $q->whereIn('slug', ['super-admin', 'kepsek']);
            })->get();

            if ($recipients->isNotEmpty()) {
                Notification::send($recipients, new AuditLogPrunedNotification($count, $cutoffDate, $triggeredBy));
            }
        }

        Setting::set(
            'audit_last_pruned_at',
            now()->toDateTimeString(),
            'datetime',
            'security',
            'Waktu Terakhir Pembersihan Audit Log'
        );

        return $count;
    }

    public function autoPruneIfDue(int $years = 3): ?int
    {
        $lastChecked = Setting::get('audit_last_auto_check_date');
        $today = now()->toDateString();

        if ($lastChecked === $today) {
            return null;
        }

        Setting::set(
            'audit_last_auto_check_date',
            $today,
            'date',
            'security',
            'Tanggal Terakhir Pengecekan Otomatis Audit Log'
        );

        $count = $this->getExpiredLogsCount($years);
        if ($count > 0) {
            return $this->pruneExpiredLogs($years, 'otomatis');
        }

        return 0;
    }

    public function getLastPrunedAt(): ?Carbon
    {
        $timestamp = Setting::get('audit_last_pruned_at');
        return $timestamp ? Carbon::parse($timestamp) : null;
    }
}
