<?php

namespace App\Notifications;

use App\Models\BillingRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BillingStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public BillingRecord $billing,
        public string $action = 'confirmed' // 'confirmed' | 'created'
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $student = $this->billing->student;
        $studentName = $student?->name ?? 'Siswa';
        $amount = $this->billing->formattedAmount();
        $desc = $this->billing->description;

        if ($this->action === 'confirmed') {
            return [
                'title'   => 'Pembayaran Berhasil Dikonfirmasi',
                'message' => "Pembayaran {$desc} untuk {$studentName} sebesar {$amount} telah diverifikasi Lunas oleh Tata Usaha.",
                'url'     => $student ? route('ortu.billing.index', $student->id) : route('ortu.dashboard'),
                'icon'    => 'bi-patch-check-fill',
            ];
        }

        return [
            'title'   => 'Tagihan Baru Diterbitkan',
            'message' => "Tagihan baru: {$desc} untuk {$studentName} sebesar {$amount} telah diterbitkan.",
            'url'     => $student ? route('ortu.billing.index', $student->id) : route('ortu.dashboard'),
            'icon'    => 'bi-cash-coin',
        ];
    }
}
