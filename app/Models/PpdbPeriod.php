<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PpdbPeriod extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'start_date', 'end_date', 'quota', 'is_active'];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(PpdbRegistration::class);
    }

    /**
     * Apakah periode ini masih menerima pendaftaran (aktif + dalam rentang tanggal
     * + kuota belum penuh, kalau kuota diset).
     */
    public function isOpenForRegistration(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $today = now()->toDateString();
        if ($today < $this->start_date->toDateString() || $today > $this->end_date->toDateString()) {
            return false;
        }

        if ($this->quota !== null && $this->registrations()->count() >= $this->quota) {
            return false;
        }

        return true;
    }
}
