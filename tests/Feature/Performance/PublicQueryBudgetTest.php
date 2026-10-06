<?php

namespace Tests\Feature\Performance;

use App\Livewire\Public\ProjectIndex;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Support\CountsQueries;
use Tests\Support\SeedsPortfolio;
use Tests\TestCase;

class PublicQueryBudgetTest extends TestCase
{
    use CountsQueries, RefreshDatabase, SeedsPortfolio;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedPortfolio();
    }

    public function test_homepage_uses_cached_data(): void
    {
        // Warm cache.
        $this->get('/')->assertOk();

        $this->startCounting();
        $this->get('/')->assertOk();

        $this->assertQueryCountAtMost(2, 'Homepage should be served from response cache.');
    }

    public function test_projects_index_eager_loads_technologies(): void
    {
        $this->startCounting();

        Livewire::test(ProjectIndex::class)->assertOk();

        $this->assertQueryCountAtMost(4, 'Project index must not N+1 on technologies.');
    }

    public function test_project_detail_eager_loads_relationships(): void
    {
        $project = Project::where('published', true)->firstOrFail();

        $this->startCounting();

        $this->get("/projects/{$project->slug}")->assertOk();

        $this->assertQueryCountAtMost(4, 'Project detail should not lazy-load.');
    }
}
