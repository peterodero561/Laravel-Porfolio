<?php

namespace App\Models;

use App\Services\PortfolioService;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    use HasUuids;

    protected $fillable = ['key', 'value'];

    public static function set(string $key, ?string $value): self
    {
        return static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        return Cache::remember(
            PortfolioService::CACHE_SETTINGS,
            now()->addHour(),
            fn () => static::query()->pluck('value', 'key')->all(),
        )[$key] ?? $default;
    }

    protected static function booted(): void
    {
        $forget = fn () => Cache::forget(PortfolioService::CACHE_SETTINGS);
        static::saved($forget);
        static::deleted($forget);
    }
}
