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
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'capacity'  => 'integer',
            'is_active' => 'boolean',
        ];
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
