<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Announcement extends Model
{
    protected $fillable = [
        'title', 'content', 'target_roles', 'priority',
        'is_published', 'published_at', 'expired_at', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'target_roles'  => 'array',
            'is_published'  => 'boolean',
            'published_at'  => 'datetime',
            'expired_at'    => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_published', true)
            ->where('published_at', '<=', now())
            ->where(fn ($sub) => $sub->whereNull('expired_at')->orWhere('expired_at', '>=', now()));
    }

    public function scopeForRole(Builder $q, string $slug): Builder
    {
        return $q->where(function ($sub) use ($slug) {
            $sub->whereJsonContains('target_roles', 'all')
                ->orWhereJsonContains('target_roles', $slug);
        });
    }

    public function isExpired(): bool
    {
        return $this->expired_at !== null && $this->expired_at->isPast();
    }

    public function priorityBadgeClass(): string
    {
        return match ($this->priority) {
            'penting' => 'text-bg-warning',
            'urgent'  => 'text-bg-danger',
            default   => 'text-bg-info',
        };
    }

    public function priorityLabel(): string
    {
        return match ($this->priority) {
            'penting' => 'Penting',
            'urgent'  => 'Mendesak',
            default   => 'Informasi',
        };
    }
}
