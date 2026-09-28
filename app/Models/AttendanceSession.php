<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'date',
        'opened_by',
        'opened_at',
        'closed_at',
        'session_qr_token',
        'session_qr_expires_at',
    ];

    protected function casts(): array
    {
        return [
            'date'                  => 'date',
            'opened_at'             => 'datetime',
            'closed_at'             => 'datetime',
            'session_qr_expires_at' => 'datetime',
        ];
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function openedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'opened_by');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function isOpen(): bool
    {
        return is_null($this->closed_at);
    }

    public function generateSessionQr(int $ttlMinutes = 5): string
    {
        $token = bin2hex(random_bytes(24));

        $this->update([
            'session_qr_token'      => $token,
            'session_qr_expires_at' => now()->addMinutes($ttlMinutes),
        ]);

        return $token;
    }

    public function isSessionQrValid(): bool
    {
        return $this->session_qr_token
            && $this->session_qr_expires_at
            && now()->lessThan($this->session_qr_expires_at);
    }

    public function sessionQrRemainingSeconds(): int
    {
        if (! $this->isSessionQrValid()) {
            return 0;
        }

        return (int) now()->diffInSeconds($this->session_qr_expires_at);
    }
}