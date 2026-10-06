<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\PortfolioService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $xml = Cache::remember(
            PortfolioService::CACHE_SITEMAP,
            now()->addHour(),
            fn () => $this->build(),
        );

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    private function build(): string
    {
        $items = [];

        $staticPages = [
            ['path' => '/', 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['path' => '/projects', 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['path' => '/about', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['path' => '/services', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['path' => '/contact', 'priority' => '0.6', 'changefreq' => 'yearly'],
            ['path' => '/resume', 'priority' => '0.5', 'changefreq' => 'monthly'],
        ];

        foreach ($staticPages as $page) {
            $items[] = [
                'loc' => url($page['path']),
                'lastmod' => now()->toAtomString(),
                'priority' => $page['priority'],
                'changefreq' => $page['changefreq'],
            ];
        }

        Project::query()
            ->where('published', true)
            ->select(['slug', 'updated_at'])
            ->orderByDesc('updated_at')
            ->chunk(200, function ($chunk) use (&$items) {
                foreach ($chunk as $project) {
                    $items[] = [
                        'loc' => route('projects.show', $project),
                        'lastmod' => $project->updated_at->toAtomString(),
                        'priority' => '0.8',
                        'changefreq' => 'monthly',
                    ];
                }
            });

        return $this->toXml($items);
    }

    /**
     * @param  array<int, array{loc: string, lastmod: string, priority: string, changefreq: string}>  $items
     */
    private function toXml(array $items): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($items as $item) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>'.htmlspecialchars($item['loc'], ENT_XML1 | ENT_QUOTES)."</loc>\n";
            $xml .= '    <lastmod>'.$item['lastmod']."</lastmod>\n";
            $xml .= '    <changefreq>'.$item['changefreq']."</changefreq>\n";
            $xml .= '    <priority>'.$item['priority']."</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }
}
