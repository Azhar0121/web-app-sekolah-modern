<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'event',
        'auditable_type',
        'auditable_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function log(string $event, string $description, ?Model $auditable = null, ?array $oldValues = null, ?array $newValues = null): self
    {
        $user = auth()->user();

        return static::create([
            'user_id'        => $user?->id,
            'user_name'      => $user?->name ?? 'Guest',
            'event'          => $event,
            'auditable_type' => $auditable ? get_class($auditable) : null,
            'auditable_id'   => $auditable?->getKey(),
            'description'    => $description,
            'old_values'     => $oldValues,
            'new_values'     => $newValues,
            'ip_address'     => request()->ip(),
            'user_agent'     => request()->userAgent(),
        ]);
    }
}
