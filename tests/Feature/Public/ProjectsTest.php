<?php

namespace Tests\Feature\Public;

use App\Livewire\Public\ProjectIndex;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Support\SeedsPortfolio;
use Tests\TestCase;

class ProjectsTest extends TestCase
{
    use RefreshDatabase, SeedsPortfolio;

    public function test_projects_index_loads(): void
    {
        $this->seedPortfolio();

        $this->get('/projects')->assertOk()->assertSee('Projects');
    }

    public function test_projects_index_lists_only_published(): void
    {
        $published = Project::factory()->create([
            'title' => 'Visible Project',
            'published' => true,
        ]);

        $draft = Project::factory()->create([
            'title' => 'Hidden Project',
            'published' => false,
        ]);

        Livewire::test(ProjectIndex::class)
            ->assertSee('Visible Project')
            ->assertDontSee('Hidden Project');
    }

    public function test_category_filter_works(): void
    {
        Project::factory()->create([
            'title' => 'Web App',
            'published' => true,
            'category' => 'web',
        ]);

        Project::factory()->create([
            'title' => 'Mobile App',
            'published' => true,
            'category' => 'mobile',
        ]);

        Livewire::test(ProjectIndex::class)
            ->set('category', 'mobile')
            ->assertSee('Mobile App')
            ->assertDontSee('Web App')
            ->set('category', 'all')
            ->assertSee('Web App')
            ->assertSee('Mobile App');
    }

    public function test_search_filters_by_title(): void
    {
        Project::factory()->create(['title' => 'ShifTenant', 'published' => true]);
        Project::factory()->create(['title' => 'Other Thing', 'published' => true]);

        Livewire::test(ProjectIndex::class)
            ->set('search', 'Shift')
            ->assertSee('ShifTenant')
            ->assertDontSee('Other Thing');
    }

    public function test_pagination_limits_results(): void
    {
        Project::factory()->count(20)->create(['published' => true]);

        Livewire::test(ProjectIndex::class)
            ->assertViewHas('projects', fn ($p) => $p->count() === 12)
            ->call('gotoPage', 2)
            ->assertViewHas('projects', fn ($p) => $p->count() === 8);
    }

    public function test_project_detail_renders_for_published(): void
    {
        $tech = Technology::factory()->create(['name' => 'Laravel']);

        $project = Project::factory()->create([
            'title' => 'ShifTenant',
            'slug' => 'shifttenant',
            'published' => true,
            'problem' => 'The problem statement',
            'solution' => 'The solution statement',
        ]);
        $project->technologies()->attach($tech);

        $this->get('/projects/shifttenant')
            ->assertOk()
            ->assertSee('ShifTenant')
            ->assertSee('The problem statement')
            ->assertSee('The solution statement')
            ->assertSee('Laravel');
    }

    public function test_unpublished_project_returns_404(): void
    {
        Project::factory()->create([
            'slug' => 'hidden',
            'published' => false,
        ]);

        $this->get('/projects/hidden')->assertNotFound();
    }

    public function test_unknown_project_returns_404(): void
    {
        $this->get('/projects/does-not-exist')->assertNotFound();
    }

    public function test_empty_sections_are_not_rendered(): void
    {
        $project = Project::factory()->create([
            'slug' => 'minimal',
            'published' => true,
            'problem' => null,
            'solution' => null,
            'architecture' => null,
            'challenges' => null,
            'results' => null,
            'features' => null,
        ]);

        $html = $this->get('/projects/minimal')->assertOk()->getContent();

        $this->assertStringNotContainsString('>Problem<', $html);
        $this->assertStringNotContainsString('>Architecture<', $html);
        $this->assertStringNotContainsString('>Challenges<', $html);
    }
}
