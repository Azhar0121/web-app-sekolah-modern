<?php

namespace App\Notifications;

use App\Models\ReportCard;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReportCardUploadedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public ReportCard $reportCard,
        public string $targetRole = 'ortu'
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $student = $this->reportCard->student;
        $studentName = $student?->name ?? 'Siswa';
        $semesterName = $this->reportCard->semester?->name ?? 'Semester';
        $label = $this->reportCard->label ? " ({$this->reportCard->label})" : '';

        $url = $this->targetRole === 'ortu' && $student
            ? route('ortu.report-cards.index', $student->id, false)
            : route('siswa.dashboard', [], false);

        $message = $this->targetRole === 'ortu'
            ? "Rapor Digital {$semesterName}{$label} untuk {$studentName} telah diunggah dan siap diunduh."
            : "Rapor Digital {$semesterName}{$label} Anda telah diunggah dan siap diunduh.";

        return [
            'title'   => 'Rapor Digital Baru',
            'message' => $message,
            'url'     => $url,
            'icon'    => 'bi-file-earmark-pdf-fill',
        ];
    }
}
