<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value.
     */
    public static function set(string $key, $value): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget("setting_{$key}");
    }

    /**
     * Get app logo URL.
     */
    public static function logo(): ?string
    {
        $logo = self::get('app_logo');
        return $logo ? asset('storage/' . $logo) : null;
    }

    /**
     * Get app name.
     */
    public static function appName(): string
    {
        return self::get('app_name', config('app.name', 'Presensi'));
    }

    /**
     * Get late tolerance in minutes.
     */
    public static function lateToleranceMinutes(): int
    {
        return (int) self::get('late_tolerance_minutes', 15);
    }

    /**
     * Get clock out early minutes.
     */
    public static function clockOutEarlyMinutes(): int
    {
        return (int) self::get('clock_out_early_minutes', 30);
    }
}
