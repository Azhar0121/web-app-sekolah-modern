<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class OsisMember extends Model
{
    protected $fillable = [
        'name',
        'position',
        'department',
        'class_name',
        'photo',
        'period',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'order'     => 'integer',
            'is_active' => 'boolean',
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
}
