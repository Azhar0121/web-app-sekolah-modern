<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommunicationThread extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'teacher_id',
        'parent_id',
        'subject',
        'category',
        'status',
        'last_message_at',
    ];

    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(CommunicationMessage::class, 'thread_id');
    }

    public function latestMessage()
    {
        return $this->hasOne(CommunicationMessage::class, 'thread_id')->latestOfMany();
    }

    public function categoryBadgeClass(): string
    {
        return match ($this->category) {
            'akademik'     => 'bg-primary-subtle text-primary border border-primary-subtle',
            'kedisiplinan' => 'bg-danger-subtle text-danger border border-danger-subtle',
            'kehadiran'    => 'bg-warning-subtle text-warning border border-warning-subtle',
            default        => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
        };
    }

    public function categoryLabel(): string
    {
        return match ($this->category) {
            'akademik'     => 'Akademik',
            'kedisiplinan' => 'Kedisiplinan',
            'kehadiran'    => 'Kehadiran',
            default        => 'Lainnya',
        };
    }

    public function unreadCountForUser(int $userId): int
    {
        return $this->messages()
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->count();
    }
}
