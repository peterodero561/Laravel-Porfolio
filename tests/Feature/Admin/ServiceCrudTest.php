<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Services\Index;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ServiceCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs($this->admin());
    }

    public function test_admin_can_create_a_service(): void
    {
        Livewire::test(Index::class)
            ->call('create')
            ->set('title', 'API Development')
            ->set('description', 'Well-designed REST APIs.')
            ->set('technologies_text', 'Laravel, OpenAPI')
            ->set('published', true)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('services', ['title' => 'API Development']);
    }

    public function test_admin_can_toggle_publish_state(): void
    {
        $service = Service::factory()->create(['published' => true]);

        Livewire::test(Index::class)->call('togglePublished', $service->id);

        $this->assertFalse($service->fresh()->published);
    }

    public function test_admin_can_delete_a_service(): void
    {
        $service = Service::factory()->create();

        Livewire::test(Index::class)->call('delete', $service->id);

        $this->assertDatabaseMissing('services', ['id' => $service->id]);
    }
}
