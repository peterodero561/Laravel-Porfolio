<?php

namespace Tests\Feature\Public;

use App\Models\Project;
use App\Models\SiteSetting;
use App\Services\PortfolioService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        SiteSetting::set('name', 'Test Person');
        SiteSetting::set('professional_title', 'Software Developer');
        SiteSetting::set('short_bio', 'I build things.');

        app(PortfolioService::class)->forget(
            PortfolioService::CACHE_SETTINGS,
        );
    }

    public function test_homepage_has_canonical_and_person_jsonld(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('<link rel="canonical"', $html);
        $this->assertStringContainsString('"@type":"Person"', $html);
        $this->assertStringContainsString('Test Person', $html);
    }

    public function test_project_detail_has_article_og_and_softwareapplication(): void
    {
        $project = Project::factory()->create([
            'slug' => 'demo',
            'title' => 'Demo App',
            'short_description' => 'A demo project.',
            'published' => true,
        ]);

        $html = $this->get('/projects/demo')->assertOk()->getContent();

        $this->assertStringContainsString('property="og:type" content="article"', $html);
        $this->assertStringContainsString('"@type":"SoftwareApplication"', $html);
        $this->assertStringContainsString('"@type":"BreadcrumbList"', $html);
        $this->assertStringContainsString('Demo App', $html);
    }

    public function test_sitemap_lists_published_projects_only(): void
    {
        Project::factory()->create(['slug' => 'shown', 'published' => true]);
        Project::factory()->create(['slug' => 'hidden', 'published' => false]);

        $xml = $this->get('/sitemap.xml')->assertOk()->getContent();

        $this->assertStringContainsString('/projects/shown', $xml);
        $this->assertStringNotContainsString('/projects/hidden', $xml);
        $this->assertStringContainsString('<?xml version="1.0"', $xml);
    }

    public function test_robots_disallows_admin_and_points_to_sitemap(): void
    {
        $body = $this->get('/robots.txt')->assertOk()->getContent();

        $this->assertStringContainsString('Disallow: /admin', $body);
        $this->assertStringContainsString('Sitemap:', $body);
        $this->assertStringContainsString('/sitemap.xml', $body);
    }

    public function test_publishing_a_project_invalidates_sitemap_cache(): void
    {
        $project = Project::factory()->create(['slug' => 'first', 'published' => true]);

        $this->get('/sitemap.xml')->assertSee('/projects/first', escape: false);

        $second = Project::factory()->create(['slug' => 'second', 'published' => false]);
        $second->update(['published' => true]);

        $this->get('/sitemap.xml')->assertSee('/projects/second', escape: false);
    }
}
