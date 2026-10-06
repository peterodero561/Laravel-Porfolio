<?php

namespace App\Services;

use App\Models\Experience;
use App\Models\Project;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Skill;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

final class PortfolioService
{
    public const CACHE_FEATURED_PROJECTS = 'portfolio.featured_projects';

    public const CACHE_PROJECTS = 'portfolio.projects';

    public const CACHE_SKILLS = 'portfolio.skills';

    public const CACHE_SERVICES = 'portfolio.services';

    public const CACHE_EXPERIENCE = 'portfolio.experience';

    public const CACHE_SETTINGS = 'portfolio.settings';

    public const CACHE_SITEMAP = 'portfolio.sitemap';

    /** @return Collection<int, Project> */
    public function featuredProjects(int $limit = 6): Collection
    {
        return Cache::remember(
            self::CACHE_FEATURED_PROJECTS,
            now()->addMinutes(30),
            fn () => Project::query()
                ->select([
                    'id', 'title', 'slug', 'short_description', 'thumbnail',
                    'category', 'project_date', 'sort_order',
                ])
                ->with('technologies:id,name,slug')
                ->where('published', true)
                ->where('featured', true)
                ->orderBy('sort_order')
                ->orderByDesc('project_date')
                ->limit($limit)
                ->get(),
        );
    }

    /**
     * @return array<string, Collection<int, Skill>>
     */
    public function skillsByCategory(): array
    {
        /** @var Collection<int, Skill> $skills */
        $skills = Cache::remember(
            self::CACHE_SKILLS,
            now()->addHour(),
            fn () => Skill::query()
                ->select(['id', 'name', 'category', 'icon', 'sort_order'])
                ->where('published', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
        );

        return $skills
            ->groupBy(fn (Skill $s) => $s->category->value)
            ->all();
    }

    /** @return Collection<int, Experience> */
    public function experience(): Collection
    {
        return Cache::remember(
            self::CACHE_EXPERIENCE,
            now()->addHour(),
            fn () => Experience::query()
                ->select([
                    'id', 'company', 'position', 'location', 'start_date',
                    'end_date', 'is_current', 'description', 'responsibilities',
                    'technologies', 'sort_order',
                ])
                ->orderByDesc('is_current')
                ->orderByDesc('start_date')
                ->orderBy('sort_order')
                ->get(),
        );
    }

    /** @return Collection<int, Service> */
    public function services(): Collection
    {
        return Cache::remember(
            self::CACHE_SERVICES,
            now()->addHour(),
            fn () => Service::query()
                ->select(['id', 'title', 'description', 'icon', 'technologies', 'sort_order'])
                ->where('published', true)
                ->orderBy('sort_order')
                ->get(),
        );
    }

    /**
     * @return array<string, string|null>
     */
    public function settings(): array
    {
        return Cache::remember(
            self::CACHE_SETTINGS,
            now()->addHour(),
            fn () => SiteSetting::query()->pluck('value', 'key')->all(),
        );
    }

    public function setting(string $key, ?string $default = null): ?string
    {
        return $this->settings()[$key] ?? $default;
    }

    /**
     * Manual invalidation hook for admin actions that touch pivots or
     * batch operations. Individual model events cover the common case.
     */
    public function forget(string ...$keys): void
    {
        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }

    public function flushPageCache(): void
    {
        foreach (['home', 'about', 'services', 'resume'] as $route) {
            // Cache tags would be ideal but the database driver doesn't support them.
            // A short TTL plus explicit flush on every content write is the pragmatic trade.
            Cache::forget('page:'.$route);
        }
    }
}
