<?php

namespace Tests\Feature\Public;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ErrorPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_404_uses_custom_error_page(): void
    {
        $html = $this->get('/this-route-does-not-exist')->assertNotFound()->getContent();

        $this->assertStringContainsString('Page not found', $html);
        $this->assertStringContainsString('Back to homepage', $html);
    }

    public function test_unpublished_project_renders_404_page(): void
    {
        Project::factory()->create(['slug' => 'draft', 'published' => false]);

        $html = $this->get('/projects/draft')->assertNotFound()->getContent();

        $this->assertStringContainsString('Page not found', $html);
    }

    public function test_admin_returns_403_for_non_admin_user(): void
    {
        $this->actingAs($this->nonAdmin());

        $this->get('/admin')->assertForbidden();
    }
}
