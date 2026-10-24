<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinanceCategory extends Model
{
    protected $fillable = ['name', 'type', 'system_key', 'description', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(FinanceTransaction::class);
    }

    public function isSystem(): bool
    {
        return ! empty($this->system_key);
    }

    public function typeLabel(): string
    {
        return $this->type === 'income' ? 'Pemasukan' : 'Pengeluaran';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /** Kategori yang boleh dipilih manual di form (kategori otomatis tagihan siswa disembunyikan). */
    public function scopeManualSelectable($query, string $type)
    {
        return $query->where('type', $type)
            ->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('system_key')->orWhere('system_key', '!=', 'tagihan_siswa'))
            ->orderBy('name');
    }
}
