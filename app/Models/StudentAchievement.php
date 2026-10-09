<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class StudentAchievement extends Model
{
    protected $fillable = [
        'title',
        'category',
        'level',
        'year',
        'organizer',
        'student_name',
        'student_class',
        'description',
        'photo',
        'is_featured',
        'is_active',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'year'        => 'integer',
            'is_featured' => 'boolean',
            'is_active'   => 'boolean',
            'order'       => 'integer',
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

    public function levelLabel(): string
    {
        return match (strtolower($this->level)) {
            'internasional' => 'Tingkat Internasional',
            'nasional'      => 'Tingkat Nasional',
            'provinsi'      => 'Tingkat Provinsi',
            'kota'          => 'Tingkat Kota / Kabupaten',
            default         => 'Tingkat Sekolah',
        };
    }

    public function levelBadgeColor(): string
    {
        return match (strtolower($this->level)) {
            'internasional' => 'bg-danger text-white',
            'nasional'      => 'bg-warning text-dark',
            'provinsi'      => 'bg-primary text-white',
            'kota'          => 'bg-success text-white',
            default         => 'bg-secondary text-white',
        };
    }

    public function categoryLabel(): string
    {
        return match (strtolower($this->category)) {
            'akademik'     => 'Akademik',
            'seni'         => 'Seni & Budaya',
            'olahraga'     => 'Olahraga',
            default        => 'Non-Akademik',
        };
    }
}
