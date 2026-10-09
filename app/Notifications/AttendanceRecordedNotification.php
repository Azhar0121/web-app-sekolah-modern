<?php

namespace App\Notifications;

use App\Models\Attendance;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AttendanceRecordedNotification extends Notification
{
    use Queueable;

    public function __construct(public Attendance $attendance)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $student = $this->attendance->student;
        $studentName = $student?->name ?? 'Anak Anda';
        
        $session = $this->attendance->session;
        $subject = $session?->schedule?->teachingAssignment?->subject?->name ?? 'Pelajaran';
        $classroom = $session?->schedule?->teachingAssignment?->classroom?->name ?? '';
        
        $status = strtolower($this->attendance->status);
        $statusLabels = [
            'hadir' => 'Hadir',
            'izin'  => 'Izin',
            'sakit' => 'Sakit',
            'alpha' => 'Alpha (Tidak Hadir)',
        ];
        $statusText = $statusLabels[$status] ?? ucfirst($status);

        $icon = match ($status) {
            'hadir' => 'bi-check-circle-fill',
            'izin', 'sakit' => 'bi-info-circle-fill',
            default => 'bi-exclamation-triangle-fill',
        };

        $dateStr = $session?->date ? \Carbon\Carbon::parse($session->date)->translatedFormat('d M Y') : now()->translatedFormat('d M Y');

        $url = $student ? route('ortu.attendance.index', $student->id, false) : route('ortu.dashboard', [], false);

        return [
            'title'   => "Presensi: {$studentName} ({$statusText})",
            'message' => "{$studentName} tercatat {$statusText} pada mapel {$subject}" . ($classroom ? " ({$classroom})" : "") . " tanggal {$dateStr}.",
            'url'     => $url,
            'icon'    => $icon,
        ];
    }
}
