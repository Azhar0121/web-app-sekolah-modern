<?php

namespace App\Notifications;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LeaveRequestSubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(public LeaveRequest $leaveRequest)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $studentName = $this->leaveRequest->student?->name ?? 'Siswa';
        $typeLabel   = $this->leaveRequest->typeLabel();
        $startDate   = $this->leaveRequest->start_date?->translatedFormat('d M Y') ?? '-';
        $endDate     = $this->leaveRequest->end_date?->translatedFormat('d M Y') ?? '-';

        return [
            'title'   => 'Pengajuan Izin Siswa Baru',
            'message' => "Orang tua {$studentName} mengajukan izin {$typeLabel} ({$startDate} s/d {$endDate}).",
            'url'     => route('guru.leave-requests.index', [], false),
            'icon'    => 'bi-file-earmark-medical',
        ];
    }
}
