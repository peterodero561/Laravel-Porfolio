<?php

namespace Tests\Feature\Admin;

use App\Models\ContactMessage;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_shows_correct_counts(): void
    {
        Project::factory()->count(5)->create(['published' => true, 'featured' => true]);
        Project::factory()->count(3)->create(['published' => false, 'featured' => false]);
        ContactMessage::factory()->count(2)->create(['read_at' => null]);
        ContactMessage::factory()->count(4)->create(['read_at' => now()]);

        $this->actingAs($this->admin());

        $this->get('/admin')
            ->assertOk()
            ->assertSee('8')   // total projects
            ->assertSee('5')   // published
            ->assertSee('5')   // featured (same 5 rows)
            ->assertSee('2');  // unread messages
    }

    public function test_dashboard_stats_are_cached(): void
    {
        Project::factory()->count(3)->create(['published' => true]);

        $this->actingAs($this->admin());

        // First hit — populate cache.
        $this->get('/admin')->assertOk();

        // Create another project. Model events invalidate the cache.
        Project::factory()->create(['published' => true]);

        // Second hit — should reflect the new count.
        $this->get('/admin')
            ->assertOk()
            ->assertSee('4');
    }
}
