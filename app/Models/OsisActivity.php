<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class OsisActivity extends Model
{
    protected $fillable = [
        'title',
        'date',
        'description',
        'photo',
        'gallery',
        'is_featured',
        'is_active',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'date'        => 'date',
            'gallery'     => 'array',
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

    public function galleryUrls(): array
    {
        $urls = [];

        if ($main = $this->photoUrl()) {
            $urls[] = $main;
        }

        if (is_array($this->gallery)) {
            foreach ($this->gallery as $item) {
                if ($item && Storage::disk('public')->exists($item)) {
                    $urls[] = Storage::url($item);
                }
            }
        }

        return array_values(array_unique($urls));
    }
}
