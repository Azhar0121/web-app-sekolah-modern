<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
    ];

    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, $value, string $type = 'text', string $group = 'general', ?string $label = null): self
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type'  => $type,
                'group' => $group,
                'label' => $label,
            ]
        );
    }
}
