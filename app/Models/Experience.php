<?php

namespace App\Models;

use App\Services\PortfolioService;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Experience extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'company', 'position', 'location', 'start_date', 'end_date',
        'is_current', 'description', 'responsibilities', 'technologies', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_current' => 'boolean',
            'responsibilities' => 'array',
            'technologies' => 'array',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        $forget = fn () => Cache::forget(PortfolioService::CACHE_EXPERIENCE);
        static::saved($forget);
        static::deleted($forget);
    }
}
