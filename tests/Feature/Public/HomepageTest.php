<?php

namespace Tests\Feature\Public;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\SeedsPortfolio;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    use RefreshDatabase, SeedsPortfolio;

    public function test_homepage_loads(): void
    {
        $this->seedPortfolio();

        $this->get('/')
            ->assertOk()
            ->assertSee('Test Person')
            ->assertSee('Software Developer');
    }

    public function test_homepage_renders_featured_projects(): void
    {
        $this->seedPortfolio();

        $featured = Project::query()
            ->where('featured', true)
            ->where('published', true)
            ->take(3)
            ->pluck('title');

        $response = $this->get('/');

        foreach ($featured as $title) {
            $response->assertSee($title, escape: false);
        }
    }

    public function test_homepage_has_no_auth_links(): void
    {
        $this->seedPortfolio();

        $this->get('/')
            ->assertDontSee('Sign in')
            ->assertDontSee('Register')
            ->assertDontSee('/admin');
    }

    public function test_homepage_has_seo_meta(): void
    {
        $this->seedPortfolio();

        $this->get('/')
            ->assertSee('<link rel="canonical"', escape: false)
            ->assertSee('"@type":"Person"', escape: false)
            ->assertSee('og:title', escape: false);
    }
}
