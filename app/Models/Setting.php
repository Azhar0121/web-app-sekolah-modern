<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
    ];

    protected static ?array $cachedSettings = null;

    public static function allKeyed(): array
    {
        if (static::$cachedSettings === null) {
            try {
                if (Schema::hasTable('settings')) {
                    static::$cachedSettings = static::all()->pluck('value', 'key')->all();
                } else {
                    static::$cachedSettings = [];
                }
            } catch (\Throwable $e) {
                static::$cachedSettings = [];
            }
        }

        return static::$cachedSettings;
    }

    public static function clearCache(): void
    {
        static::$cachedSettings = null;
    }

    public static function get(string $key, $default = null)
    {
        $all = static::allKeyed();
        if (array_key_exists($key, $all)) {
            return $all[$key];
        }

        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, $value, string $type = 'text', string $group = 'general', ?string $label = null): self
    {
        static::clearCache();

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
