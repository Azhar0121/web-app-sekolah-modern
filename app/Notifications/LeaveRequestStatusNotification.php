<?php

namespace App\Notifications;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LeaveRequestStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public LeaveRequest $leaveRequest,
        public string $targetRole = 'ortu'
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $studentName = $this->leaveRequest->student?->name ?? 'Siswa';
        $typeLabel   = $this->leaveRequest->typeLabel();
        $isApproved  = $this->leaveRequest->status === 'approved';
        $statusText  = $isApproved ? 'disetujui' : 'ditolak';
        $icon        = $isApproved ? 'bi-check-circle-fill' : 'bi-x-circle-fill';

        $url = $this->targetRole === 'ortu'
            ? route('ortu.leave-requests.index', [], false)
            : route('siswa.dashboard', [], false);

        $message = $this->targetRole === 'ortu'
            ? "Pengajuan izin {$typeLabel} untuk {$studentName} telah {$statusText} oleh guru."
            : "Pengajuan izin {$typeLabel} Anda telah {$statusText} oleh guru.";

        if (! empty($this->leaveRequest->process_notes)) {
            $message .= " Catatan: \"{$this->leaveRequest->process_notes}\"";
        }

        return [
            'title'   => 'Pengajuan Izin ' . ($isApproved ? 'Disetujui' : 'Ditolak'),
            'message' => $message,
            'url'     => $url,
            'icon'    => $icon,
        ];
    }
}
