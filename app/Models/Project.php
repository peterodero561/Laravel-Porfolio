<?php

namespace App\Models;

use App\Enums\ProjectCategory;
use App\Services\PortfolioService;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'title', 'slug', 'short_description', 'full_description', 'thumbnail',
        'category', 'github_url', 'live_url', 'featured', 'published',
        'sort_order', 'project_date', 'problem', 'solution', 'features',
        'architecture', 'technical_implementation', 'challenges', 'results',
    ];

    protected function casts(): array
    {
        return [
            'category' => ProjectCategory::class,
            'featured' => 'boolean',
            'published' => 'boolean',
            'sort_order' => 'integer',
            'project_date' => 'date',
            'features' => 'array',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class)->withTimeStamps();
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order');
    }

    protected static function booted(): void
    {
        static::saving(function (self $project) {
            if (blank($project->slug) && filled($project->title)) {
                $project->slug = Str::slug($project->title);
            }
        });

        static::saved(fn () => self::forgetCaches());
        static::deleted(fn () => self::forgetCaches());
    }

    /**
     * Called by ProjectImage and any admin action that mutates the
     * pivot table (technologies), since those writes don't fire
     * Project::saved automatically.
     */
    public static function forgetCaches(): void
    {
        Cache::forget(PortfolioService::CACHE_FEATURED_PROJECTS);
        Cache::forget(PortfolioService::CACHE_PROJECTS);
        Cache::forget(PortfolioService::CACHE_SITEMAP);
    }
}
