<?php

namespace App\Support;

use App\Services\PortfolioService;
use Illuminate\Support\Facades\Cache;

trait FlushesPublicCache
{
    public static function flushPublicCache(): void
    {
        foreach ([
            PortfolioService::CACHE_FEATURED_PROJECTS,
            PortfolioService::CACHE_PROJECTS,
            PortfolioService::CACHE_SKILLS,
            PortfolioService::CACHE_SERVICES,
            PortfolioService::CACHE_EXPERIENCE,
            PortfolioService::CACHE_SETTINGS,
            PortfolioService::CACHE_SITEMAP,
        ] as $key) {
            Cache::forget($key);
        }

        app(PortfolioService::class)->flushPageCache();
    }

    protected static function bootFlushesPublicCache(): void
    {
        static::saved(fn () => static::flushPublicCache());
        static::deleted(fn () => static::flushPublicCache());
    }
}
