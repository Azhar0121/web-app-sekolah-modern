<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'inventory_category_id',
        'room_id',
        'quantity',
        'unit',
        'condition',
        'source_of_fund',
        'procurement_year',
        'price_per_unit',
        'photo_path',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'quantity'         => 'integer',
            'procurement_year' => 'integer',
            'price_per_unit'   => 'decimal:2',
        ];
    }

    // Relasi
    public function category(): BelongsTo
    {
        return $this->belongsTo(InventoryCategory::class, 'inventory_category_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helper Status & Label
    public function conditionLabel(): string
    {
        return match ($this->condition) {
            'good'         => 'Baik',
            'light_damage' => 'Rusak Ringan',
            'heavy_damage' => 'Rusak Berat',
            'lost'         => 'Hilang',
            default        => 'Tidak Diketahui',
        };
    }

    public function conditionBadgeClass(): string
    {
        return match ($this->condition) {
            'good'         => 'bg-success-subtle text-success border border-success-subtle',
            'light_damage' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
            'heavy_damage' => 'bg-danger-subtle text-danger border border-danger-subtle',
            'lost'         => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
            default        => 'bg-light text-dark',
        };
    }

    public function photoUrl(): ?string
    {
        if ($this->photo_path && Storage::disk('public')->exists($this->photo_path)) {
            return Storage::url($this->photo_path);
        }
        return null;
    }

    public function formattedPrice(): string
    {
        if (! $this->price_per_unit) {
            return '-';
        }
        return 'Rp ' . number_format($this->price_per_unit, 0, ',', '.');
    }

    public function formattedTotalValue(): string
    {
        if (! $this->price_per_unit) {
            return '-';
        }
        return 'Rp ' . number_format($this->price_per_unit * $this->quantity, 0, ',', '.');
    }

    public static function generateCode(?int $year = null): string
    {
        $year = $year ?: date('Y');
        $prefix = "INV-{$year}-";

        $last = static::where('code', 'like', "{$prefix}%")
            ->orderByDesc('id')
            ->value('code');

        if ($last && preg_match('/INV-\d{4}-(\d+)/', $last, $matches)) {
            $next = ((int) $matches[1]) + 1;
        } else {
            $next = 1;
        }

        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}