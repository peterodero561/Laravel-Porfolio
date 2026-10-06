<?php

namespace App\Models;

use App\Services\PortfolioService;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Service extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'title', 'description', 'icon', 'technologies', 'published', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'technologies' => 'array',
            'published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        $forget = fn () => Cache::forget(PortfolioService::CACHE_SERVICES);
        static::saved($forget);
        static::deleted($forget);
    }
}
