<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    protected $fillable = [
        'parent_id', 'student_id', 'type', 'start_date', 'end_date',
        'reason', 'attachment_path', 'attachment_original_name',
        'status', 'processed_by', 'processed_at', 'process_notes',
    ];

    protected function casts(): array
    {
        return [
            'start_date'   => 'date',
            'end_date'     => 'date',
            'processed_at' => 'datetime',
        ];
    }

    public function parent(): BelongsTo { return $this->belongsTo(User::class, 'parent_id'); }
    public function student(): BelongsTo { return $this->belongsTo(User::class, 'student_id'); }
    public function processedBy(): BelongsTo { return $this->belongsTo(User::class, 'processed_by'); }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default    => 'Menunggu',
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'approved' => 'text-bg-success',
            'rejected' => 'text-bg-danger',
            default    => 'text-bg-warning',
        };
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'sakit' => 'Sakit',
            'izin'  => 'Izin',
            default => $this->type,
        };
    }

    public function durationDays(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }
}
