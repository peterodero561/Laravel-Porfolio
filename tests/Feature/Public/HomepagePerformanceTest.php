<?php

namespace Tests\Feature\Public;

use App\Models\Experience;
use App\Models\Project;
use App\Models\Service;
use App\Models\Skill;
use App\Models\Technology;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\CountsQueries;
use Tests\TestCase;

class HomepagePerformanceTest extends TestCase
{
    use CountsQueries, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed a realistic dataset
        $tech = Technology::factory()->count(6)->create();

        Project::factory()
            ->count(8)
            ->featured()
            ->create()
            ->each(fn ($p) => $p->technologies()->attach($tech->random(3)->pluck('id')));

        Skill::factory()->count(20)->create();
        Experience::factory()->count(4)->create();
        Service::factory()->count(6)->create();

        // Warm the cache so we measure the steady state, not the cold path.
        $this->get('/')->assertOk();
    }

    public function test_homepage_stays_within_query_budget(): void
    {
        $this->startCounting();

        $this->get('/')->assertOk();

        $this->assertQueryCountAtMost(2, 'Homepage should hit only the response cache plus a health check.');
    }

    public function test_about_page_stays_within_query_budget(): void
    {
        $this->get('/about')->assertOk(); // warm

        $this->startCounting();
        $this->get('/about')->assertOk();

        $this->assertQueryCountAtMost(2);
    }

    public function test_services_page_stays_within_query_budget(): void
    {
        $this->get('/services')->assertOk(); // warm

        $this->startCounting();
        $this->get('/services')->assertOk();

        $this->assertQueryCountAtMost(2);
    }
}
