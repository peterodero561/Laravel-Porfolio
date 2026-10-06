<?php

namespace App\Support;

use App\Models\Project;

final class StructuredData
{
    /**
     * @param  array<string, string|null>  $settings
     * @return array<string, mixed>
     */
    public static function person(array $settings): array
    {
        $sameAs = array_values(array_filter([
            $settings['github_url'] ?? null,
            $settings['linkedin_url'] ?? null,
            $settings['twitter_url'] ?? null,
            $settings['website_url'] ?? null,
        ]));

        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $settings['name'] ?? config('app.name'),
            'jobTitle' => $settings['professional_title'] ?? null,
            'url' => url('/'),
            'description' => $settings['short_bio'] ?? null,
        ];

        if (! empty($settings['profile_image'])) {
            $data['image'] = url(\Storage::disk('public')->url($settings['profile_image']));
        }

        if ($sameAs !== []) {
            $data['sameAs'] = $sameAs;
        }

        return array_filter($data, fn ($v) => $v !== null);
    }

    /**
     * @param  array<string, string|null>  $settings
     * @return array<string, mixed>
     */
    public static function website(array $settings): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $settings['name'] ?? config('app.name'),
            'url' => url('/'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function softwareApplication(Project $project, array $settings): array
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'SoftwareApplication',
            'name' => $project->title,
            'description' => $project->short_description,
            'applicationCategory' => match ($project->category->value) {
                'mobile' => 'MobileApplication',
                'web' => 'WebApplication',
                default => 'BusinessApplication',
            },
            'operatingSystem' => match ($project->category->value) {
                'mobile' => 'iOS, Android',
                'web' => 'Web',
                default => 'Any',
            },
            'url' => route('projects.show', $project),
            'author' => [
                '@type' => 'Person',
                'name' => $settings['name'] ?? config('app.name'),
                'url' => url('/'),
            ],
        ];

        if ($project->project_date) {
            $data['datePublished'] = $project->project_date->toDateString();
        }

        if ($project->thumbnail) {
            $data['image'] = url(\Storage::disk('public')->url($project->thumbnail));
        }

        return array_filter($data, fn ($v) => $v !== null);
    }

    /**
     * @param  array<int, array{name: string, url: string}>  $items
     * @return array<string, mixed>
     */
    public static function breadcrumb(array $items): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => array_map(
                fn (array $item, int $i) => [
                    '@type' => 'ListItem',
                    'position' => $i + 1,
                    'name' => $item['name'],
                    'item' => $item['url'],
                ],
                $items,
                array_keys($items),
            ),
        ];
    }
}
