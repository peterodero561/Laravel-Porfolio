<?php

namespace App\Models;

use App\Enums\SkillCategory;
use App\Services\PortfolioService;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Skill extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'category', 'icon', 'published', 'sort_order'];

    protected function casts(): array
    {
        return [
            'category' => SkillCategory::class,
            'published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        $forget = fn () => Cache::forget(PortfolioService::CACHE_SKILLS);
        static::saved($forget);
        static::deleted($forget);
    }
}
