<?php

namespace Tests\Feature\Public;

use App\Livewire\Public\ProjectIndex;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Support\CountsQueries;
use Tests\TestCase;

class ProjectsPerformanceTest extends TestCase
{
    use CountsQueries, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $tech = Technology::factory()->count(5)->create();

        Project::factory()->count(24)->create()->each(
            fn ($p) => $p->technologies()->attach($tech->random(2)->pluck('id')),
        );
    }

    public function test_projects_index_uses_eager_loading(): void
    {
        $this->startCounting();

        Livewire::test(ProjectIndex::class)
            ->assertOk();

        // Expected: count query, one paginated projects query, one technologies eager load.
        $this->assertQueryCountAtMost(4, 'Project listing must eager-load technologies; got: '.$this->formatQueries());
    }

    public function test_project_detail_stays_within_query_budget(): void
    {
        $project = Project::first();

        $this->startCounting();

        $this->get("/projects/{$project->slug}")->assertOk();

        // Bound model + eager-load technologies + eager-load images.
        $this->assertQueryCountAtMost(4, 'Project detail should not lazy-load relationships.');
    }
}
