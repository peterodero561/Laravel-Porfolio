<?php

namespace App\Services;

use App\Models\ContactMessage;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Service;
use App\Models\Skill;
use Illuminate\Support\Facades\Cache;

final class AdminStatsService
{
    private const TTL = 60; // seconds — short enough to feel live, long enough to avoid thrash

    /**
     * @return array<string, int>
     */
    public function snapshot(): array
    {
        return Cache::remember('admin.stats', now()->addSeconds(self::TTL), fn () => [
            'projects_total' => Project::count(),
            'projects_published' => Project::where('published', true)->count(),
            'projects_featured' => Project::where('featured', true)->count(),
            'skills_total' => Skill::count(),
            'experience_total' => Experience::count(),
            'services_total' => Service::count(),
            'messages_total' => ContactMessage::count(),
            'messages_unread' => ContactMessage::whereNull('read_at')->count(),
        ]);
    }

    public static function forget(): void
    {
        Cache::forget('admin.stats');
    }
}
