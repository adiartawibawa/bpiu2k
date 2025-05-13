<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'key',
        'value',
        'group'
    ];

    protected $casts = [
        'value' => 'json',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = Cache::remember('settings', now()->addDay(), function () {
            return self::all()->mapWithKeys(function ($item) {
                return [$item['key'] => $item['value']];
            })->toArray();
        });

        return $settings[$key] ?? $default;
    }

    public static function set(string $key, mixed $value, ?string $group = null): bool
    {
        $result = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group
            ]
        );

        if ($result) {
            Cache::forget('settings');
            return true;
        }

        return false;
    }

    public static function getGroup(string $group): array
    {
        return Cache::remember("settings:group:$group", now()->addDay(), function () use ($group) {
            return self::where('group', $group)
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item['key'] => $item['value']];
                })
                ->toArray();
        });
    }

    public static function clearCache(): void
    {
        Cache::forget('settings');
        Cache::flush();
    }

    public static function allCached()
    {
        return Cache::remember('settings.all', now()->addDay(), function () {
            return collect(self::all()->mapWithKeys(function ($item) {
                return [$item['key'] => $item['value']];
            }));
        });
    }

    public static function getConfig(string $key, $default = null)
    {
        return config("settings.{$key}", self::get($key, $default));
    }
}
