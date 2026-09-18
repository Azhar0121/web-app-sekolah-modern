<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        // Data resmi
        'nisn', 'nik', 'gender', 'birth_place', 'birth_date',
        'previous_school', 'parent_name', 'parent_phone',
        // Data pribadi
        'address', 'phone', 'emergency_contact_name', 'emergency_contact_phone', 'photo_path',
    ];

    public const SELF_EDITABLE_FIELDS = [
        'address', 'phone', 'emergency_contact_name', 'emergency_contact_phone', 'photo_path',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function genderLabel(): string
    {
        return match ($this->gender) {
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
            default => '-',
        };
    }

    public function hasPhoto(): bool
    {
        return ! empty($this->photo_path);
    }
}