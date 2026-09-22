<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillingRecord extends Model
{
    protected $fillable = [
        'student_id', 'academic_year_id', 'description', 'amount',
        'due_date', 'status', 'paid_at',
        'payment_proof_path', 'payment_proof_original_name',
        'confirmed_by', 'confirmed_at', 'notes', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'amount'       => 'decimal:2',
            'due_date'     => 'date',
            'paid_at'      => 'datetime',
            'confirmed_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo { return $this->belongsTo(User::class, 'student_id'); }
    public function academicYear(): BelongsTo { return $this->belongsTo(AcademicYear::class); }
    public function confirmedBy(): BelongsTo { return $this->belongsTo(User::class, 'confirmed_by'); }
    public function createdBy(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'paid'    => 'Lunas',
            'overdue' => 'Jatuh Tempo',
            default   => 'Belum Bayar',
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'paid'    => 'text-bg-success',
            'overdue' => 'text-bg-danger',
            default   => 'text-bg-warning',
        };
    }

    public function formattedAmount(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }
}
