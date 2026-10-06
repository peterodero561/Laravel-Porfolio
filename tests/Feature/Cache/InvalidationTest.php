<?php

namespace Tests\Feature\Cache;

use App\Models\Project;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Skill;
use App\Models\Technology;
use App\Services\PortfolioService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvalidationTest extends TestCase
{
    use RefreshDatabase;

    private PortfolioService $portfolio;

    protected function setUp(): void
    {
        parent::setUp();
        $this->portfolio = app(PortfolioService::class);
    }

    public function test_featuring_a_project_updates_featured_cache(): void
    {
        $a = Project::factory()->featured()->create(['published' => true]);
        $b = Project::factory()->create(['published' => true, 'featured' => false]);

        $this->assertCount(1, $this->portfolio->featuredProjects());

        $b->update(['featured' => true]);

        $this->assertCount(2, $this->portfolio->featuredProjects());
    }

    public function test_unpublishing_a_project_removes_it_from_cache(): void
    {
        $project = Project::factory()->featured()->create(['published' => true]);

        $this->assertCount(1, $this->portfolio->featuredProjects());

        $project->update(['published' => false]);

        $this->assertCount(0, $this->portfolio->featuredProjects());
    }

    public function test_updating_a_skill_refreshes_the_skills_cache(): void
    {
        $skill = Skill::factory()->create(['name' => 'Old Name', 'category' => 'backend', 'published' => true]);

        $this->assertSame('Old Name', $this->portfolio->skillsByCategory()['backend'][0]->name);

        $skill->update(['name' => 'New Name']);

        $this->assertSame('New Name', $this->portfolio->skillsByCategory()['backend'][0]->name);
    }

    public function test_deleting_a_service_refreshes_the_services_cache(): void
    {
        Service::factory()->count(3)->create();

        $this->assertCount(3, $this->portfolio->services());

        Service::first()->delete();

        $this->assertCount(2, $this->portfolio->services());
    }

    public function test_updating_settings_refreshes_the_settings_cache(): void
    {
        SiteSetting::set('name', 'Original');
        $this->assertSame('Original', $this->portfolio->setting('name'));

        SiteSetting::set('name', 'Updated');
        $this->assertSame('Updated', $this->portfolio->setting('name'));
    }

    public function test_pivot_sync_requires_explicit_cache_forget(): void
    {
        $project = Project::factory()->featured()->create(['published' => true]);
        $tech = Technology::factory()->create(['name' => 'Livewire']);

        $this->assertEmpty($this->portfolio->featuredProjects()->first()->technologies);

        $project->technologies()->sync([$tech->id]);

        // The explicit forget that the admin form calls.
        Project::forgetCaches();

        $this->assertSame(
            'Livewire',
            $this->portfolio->featuredProjects()->first()->technologies->first()->name,
        );
    }
}
