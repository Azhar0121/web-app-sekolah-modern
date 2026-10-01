<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UrlRedirect extends Model
{
    protected $fillable = [
        'source_url',
        'target_url',
        'status_code',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'status_code' => 'integer',
            'is_active'   => 'boolean',
        ];
    }
}
