<?php

namespace Tests\Feature\Admin;

use App\Jobs\OptimizeProjectImage;
use App\Livewire\Admin\Projects\Form;
use App\Livewire\Admin\Projects\Index;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ProjectCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs($this->admin());
    }

    public function test_index_loads_with_projects(): void
    {
        Project::factory()->count(3)->create();

        $this->get('/admin/projects')->assertOk()->assertSee('Projects');
    }

    public function test_admin_can_create_a_project(): void
    {
        Livewire::test(Form::class)
            ->set('title', 'New Project')
            ->set('slug', 'new-project')
            ->set('short_description', 'A brief description of the new project.')
            ->set('category', 'web')
            ->set('full_description', 'Longer body text.')
            ->set('technologies_text', 'Laravel, Livewire')
            ->set('published', true)
            ->set('featured', false)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect('/admin/projects');

        $this->assertDatabaseHas('projects', [
            'title' => 'New Project',
            'slug' => 'new-project',
            'category' => 'web',
            'published' => true,
        ]);

        $project = Project::where('slug', 'new-project')->first();
        $this->assertSame(2, $project->technologies()->count());
        $this->assertTrue($project->technologies()->where('name', 'Laravel')->exists());
    }

    public function test_creating_a_project_with_technologies_reuses_existing_records(): void
    {
        $existing = Technology::factory()->create(['name' => 'Laravel', 'slug' => 'laravel']);

        Livewire::test(Form::class)
            ->set('title', 'New Project')
            ->set('slug', 'new-project')
            ->set('short_description', 'A brief description.')
            ->set('category', 'web')
            ->set('technologies_text', 'Laravel, Flutter')
            ->call('save');

        // Laravel must not be duplicated — Flutter is created fresh.
        $this->assertSame(1, Technology::where('slug', 'laravel')->count());
        $this->assertSame(1, Technology::where('slug', 'flutter')->count());
        $this->assertSame(2, Technology::count());
    }

    public function test_admin_can_update_a_project(): void
    {
        $project = Project::factory()->create(['title' => 'Old Title', 'slug' => 'old-title']);

        Livewire::test(Form::class, ['project' => $project])
            ->assertSet('title', 'Old Title')
            ->set('title', 'Updated Title')
            ->set('short_description', 'Updated description.')
            ->set('category', 'web')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'title' => 'Updated Title',
        ]);
    }

    public function test_admin_can_delete_a_project(): void
    {
        $project = Project::factory()->create();

        Livewire::test(Index::class)
            ->call('delete', $project->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_admin_can_toggle_publish_state(): void
    {
        $project = Project::factory()->create(['published' => true]);

        Livewire::test(Index::class)->call('togglePublished', $project->id);

        $this->assertFalse($project->fresh()->published);
    }

    public function test_admin_can_toggle_featured_state(): void
    {
        $project = Project::factory()->create(['featured' => false]);

        Livewire::test(Index::class)->call('toggleFeatured', $project->id);

        $this->assertTrue($project->fresh()->featured);
    }

    public function test_slug_must_be_unique(): void
    {
        Project::factory()->create(['slug' => 'taken']);

        Livewire::test(Form::class)
            ->set('title', 'Title')
            ->set('slug', 'taken')
            ->set('short_description', 'A description.')
            ->set('category', 'web')
            ->call('save')
            ->assertHasErrors(['slug' => 'unique']);
    }

    public function test_slug_must_match_pattern(): void
    {
        Livewire::test(Form::class)
            ->set('title', 'Title')
            ->set('slug', 'Invalid Slug With Spaces')
            ->set('short_description', 'A description.')
            ->set('category', 'web')
            ->call('save')
            ->assertHasErrors(['slug' => 'regex']);
    }

    public function test_valid_urls_are_accepted_and_invalid_rejected(): void
    {
        Livewire::test(Form::class)
            ->set('title', 'Title')
            ->set('slug', 'title')
            ->set('short_description', 'Desc.')
            ->set('category', 'web')
            ->set('github_url', 'not-a-url')
            ->call('save')
            ->assertHasErrors(['github_url']);

        Livewire::test(Form::class)
            ->set('title', 'Title 2')
            ->set('slug', 'title-2')
            ->set('short_description', 'Desc.')
            ->set('category', 'web')
            ->set('github_url', 'https://github.com/example')
            ->set('live_url', 'https://example.test')
            ->call('save')
            ->assertHasNoErrors(['github_url', 'live_url']);
    }

    public function test_thumbnail_upload_is_stored(): void
    {
        $file = UploadedFile::fake()->image('thumb.jpg', 800, 600);

        Livewire::test(Form::class)
            ->set('title', 'With Thumb')
            ->set('slug', 'with-thumb')
            ->set('short_description', 'Desc.')
            ->set('category', 'web')
            ->set('thumbnail', $file)
            ->call('save');

        $project = Project::where('slug', 'with-thumb')->first();
        $this->assertNotNull($project->thumbnail);
        Storage::disk('public')->assertExists($project->thumbnail);
    }

    public function test_gallery_upload_dispatches_optimization_job(): void
    {
        Queue::fake([OptimizeProjectImage::class]);

        $files = [
            UploadedFile::fake()->image('one.jpg'),
            UploadedFile::fake()->image('two.jpg'),
        ];

        Livewire::test(Form::class)
            ->set('title', 'Gallery')
            ->set('slug', 'gallery')
            ->set('short_description', 'Desc.')
            ->set('category', 'web')
            ->set('newImages', $files)
            ->call('save');

        Queue::assertPushed(OptimizeProjectImage::class, 2);
    }

    public function test_invalid_file_type_is_rejected(): void
    {
        $file = UploadedFile::fake()->create('script.php', 100, 'text/php');

        Livewire::test(Form::class)
            ->set('title', 'Bad File')
            ->set('slug', 'bad-file')
            ->set('short_description', 'Desc.')
            ->set('category', 'web')
            ->set('thumbnail', $file)
            ->call('save')
            ->assertHasErrors(['thumbnail']);
    }

    public function test_removing_gallery_image_deletes_the_file(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('projects/gallery/x.jpg', 'fake');

        $project = Project::factory()->create();
        $image = $project->images()->create([
            'path' => 'projects/gallery/x.jpg',
            'alt' => 'Test',
            'sort_order' => 0,
        ]);

        Livewire::test(Form::class, ['project' => $project])
            ->call('removeExistingImage', $image->id);

        Storage::disk('public')->assertMissing('projects/gallery/x.jpg');
        $this->assertDatabaseMissing('project_images', ['id' => $image->id]);
    }
}
