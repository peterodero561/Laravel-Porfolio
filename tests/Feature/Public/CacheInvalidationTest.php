<?php

namespace Tests\Feature\Public;

use App\Models\Project;
use App\Models\SiteSetting;
use App\Models\Technology;
use App\Services\PortfolioService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CacheInvalidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_featuring_a_project_refreshes_featured_cache(): void
    {
        $a = Project::factory()->featured()->create(['title' => 'Alpha']);
        $b = Project::factory()->create(['title' => 'Beta']);

        $service = app(PortfolioService::class);

        $this->assertSame(1, $service->featuredProjects()->count());

        $b->update(['featured' => true]);

        $this->assertSame(2, $service->featuredProjects()->count());
    }

    public function test_updating_a_project_technologies_refreshes_cache(): void
    {
        $project = Project::factory()->featured()->create();
        $tech = Technology::factory()->create(['name' => 'Livewire']);

        $service = app(PortfolioService::class);

        $this->assertEmpty($service->featuredProjects()->first()->technologies);

        $project->technologies()->sync([$tech->id]);
        Project::forgetCaches(); // explicit — pivot sync does not fire Project::saved

        $this->assertSame('Livewire', $service->featuredProjects()->first()->technologies->first()->name);
    }

    public function test_updating_settings_refreshes_public_settings(): void
    {
        SiteSetting::set('name', 'Original');

        $service = app(PortfolioService::class);
        $this->assertSame('Original', $service->setting('name'));

        SiteSetting::set('name', 'Updated');

        $this->assertSame('Updated', $service->setting('name'));
    }
}
