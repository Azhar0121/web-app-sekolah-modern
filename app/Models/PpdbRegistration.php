<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class PpdbRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'ppdb_period_id', 'registration_number', 'full_name', 'nisn', 'nik',
        'gender', 'birth_place', 'birth_date', 'address', 'phone', 'email',
        'parent_name', 'parent_phone', 'previous_school',
        'status', 'notes', 'verified_by', 'verified_at',
        'accepted_at', 're_registration_deadline',
        're_registration_reference', 're_registration_notes',
        're_registration_confirmed_by', 're_registration_confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'verified_at' => 'datetime',
            'accepted_at' => 'datetime',
            're_registration_deadline' => 'date',
            're_registration_confirmed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (PpdbRegistration $registration) {
            if (empty($registration->registration_number)) {
                $registration->registration_number = static::generateRegistrationNumber();
            }
        });
    }

    public static function generateRegistrationNumber(): string
    {
        $year = now()->year;

        return DB::transaction(function () use ($year) {
            $lastNumber = static::where('registration_number', 'like', "PPDB-{$year}-%")
                ->lockForUpdate()
                ->count();

            $nextSequence = $lastNumber + 1;

            return sprintf('PPDB-%d-%05d', $year, $nextSequence);
        });
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(PpdbPeriod::class, 'ppdb_period_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(PpdbDocument::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function reRegistrationConfirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 're_registration_confirmed_by');
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'submitted' => 'Menunggu Verifikasi',
            'verified' => 'Terverifikasi',
            'accepted' => 'Diterima — Menunggu Daftar Ulang',
            'rejected' => 'Ditolak',
            'registered_ulang' => 'Daftar Ulang Selesai',
            default => $this->status,
        };
    }

    public function isAwaitingReRegistration(): bool
    {
        return $this->status === 'accepted';
    }

    public function isReRegistrationOverdue(): bool
    {
        return $this->isAwaitingReRegistration()
            && $this->re_registration_deadline !== null
            && now()->toDateString() > $this->re_registration_deadline->toDateString();
    }

    public function reRegistrationDeadlineLabel(): ?string
    {
        return $this->re_registration_deadline?->translatedFormat('d F Y');
    }
}