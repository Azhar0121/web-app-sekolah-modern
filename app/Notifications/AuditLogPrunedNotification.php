<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AuditLogPrunedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public int $deletedCount,
        public Carbon $cutoffDate,
        public string $triggeredBy = 'otomatis'
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $cutoffFormatted = $this->cutoffDate->translatedFormat('d F Y');

        return [
            'title'   => 'Pembersihan Riwayat Audit Log',
            'message' => "Pembersihan {$this->triggeredBy}: Sebanyak {$this->deletedCount} data audit log yang telah berusia lebih dari 3 tahun (sebelum {$cutoffFormatted}) telah berhasil dibersihkan dari sistem.",
            'url'     => route('admin.audit-logs.index', [], false),
            'icon'    => 'bi-shield-check',
        ];
    }
}
