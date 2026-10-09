<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'code',
        'name',
        'type',
        'capacity',
        'location',
        'photo',
        'gallery',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'capacity'  => 'integer',
            'gallery'   => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function photoUrl(): ?string
    {
        if ($this->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->photo)) {
            return \Illuminate\Support\Facades\Storage::url($this->photo);
        }

        return null;
    }

    public function galleryUrls(): array
    {
        $urls = [];

        if ($mainPhoto = $this->photoUrl()) {
            $urls[] = $mainPhoto;
        }

        if (is_array($this->gallery)) {
            foreach ($this->gallery as $item) {
                if ($item && \Illuminate\Support\Facades\Storage::disk('public')->exists($item)) {
                    $urls[] = \Illuminate\Support\Facades\Storage::url($item);
                }
            }
        }

        return array_values(array_unique($urls));
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'laboratorium' => 'Laboratorium',
            'perpustakaan' => 'Perpustakaan',
            'aula'         => 'Aula',
            'lapangan'     => 'Lapangan',
            default        => 'Ruang Kelas',
        };
    }
}