<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Extracurricular extends Model
{
    protected $fillable = [
        'name',
        'category',
        'description',
        'coach_name',
        'schedule_day',
        'photo',
        'is_active',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'order'     => 'integer',
        ];
    }

    public function photoUrl(): ?string
    {
        if ($this->photo && Storage::disk('public')->exists($this->photo)) {
            return Storage::url($this->photo);
        }

        return null;
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photoUrl();
    }

    public function categoryLabel(): string
    {
        return match (strtolower($this->category)) {
            'olahraga'      => 'Olahraga & Prestasi',
            'seni'          => 'Seni & Budaya',
            'teknologi'     => 'Sains & Teknologi',
            'kepemimpinan'  => 'Kepemimpinan & Organisasi',
            'keagamaan'     => 'Keagamaan & Rohani',
            default         => 'Pengembangan Bakat',
        };
    }

    public function categoryBadgeColor(): string
    {
        return match (strtolower($this->category)) {
            'olahraga'      => 'bg-danger-subtle text-danger border-danger-subtle',
            'seni'          => 'bg-warning-subtle text-warning-emphasis border-warning-subtle',
            'teknologi'     => 'bg-primary-subtle text-primary border-primary-subtle',
            'kepemimpinan'  => 'bg-success-subtle text-success border-success-subtle',
            'keagamaan'     => 'bg-info-subtle text-info-emphasis border-info-subtle',
            default         => 'bg-secondary-subtle text-secondary border-secondary-subtle',
        };
    }
}
