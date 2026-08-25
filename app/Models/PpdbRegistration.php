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
        'gender', 'birth_place', 'birth_date', 'address', 'phone',
        'parent_name', 'parent_phone', 'previous_school',
        'status', 'notes', 'verified_by', 'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'verified_at' => 'datetime',
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

    /**
     * Format: PPDB-{tahun}-{nomor urut 5 digit}, contoh: PPDB-2026-00001
     */
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

    public function statusLabel(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'submitted' => 'Menunggu Verifikasi',
            'verified' => 'Terverifikasi',
            'accepted' => 'Diterima',
            'rejected' => 'Ditolak',
            default => $this->status,
        };
    }
}
