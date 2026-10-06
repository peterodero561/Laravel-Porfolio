<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Skills\Index;
use App\Models\Skill;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SkillCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs($this->admin());
    }

    public function test_admin_can_create_a_skill(): void
    {
        Livewire::test(Index::class)
            ->call('create')
            ->set('name', 'Livewire')
            ->set('category', 'backend')
            ->set('published', true)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('skills', ['name' => 'Livewire', 'category' => 'backend']);
    }

    public function test_admin_can_update_a_skill(): void
    {
        $skill = Skill::factory()->create(['name' => 'Old Name']);

        Livewire::test(Index::class)
            ->call('edit', $skill->id)
            ->assertSet('name', 'Old Name')
            ->set('name', 'New Name')
            ->call('save');

        $this->assertSame('New Name', $skill->fresh()->name);
    }

    public function test_admin_can_delete_a_skill(): void
    {
        $skill = Skill::factory()->create();

        Livewire::test(Index::class)->call('delete', $skill->id);

        $this->assertDatabaseMissing('skills', ['id' => $skill->id]);
    }

    public function test_name_is_required(): void
    {
        Livewire::test(Index::class)
            ->call('create')
            ->set('name', '')
            ->call('save')
            ->assertHasErrors(['name' => 'required']);
    }
}
