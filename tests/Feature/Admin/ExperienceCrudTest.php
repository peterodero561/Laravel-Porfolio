<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Experience\Index;
use App\Models\Experience;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ExperienceCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs($this->admin());
    }

    public function test_admin_can_create_an_experience_entry(): void
    {
        Livewire::test(Index::class)
            ->call('create')
            ->set('company', 'Example Corp')
            ->set('position', 'Developer')
            ->set('start_date', '2023-01-01')
            ->set('end_date', '2024-01-01')
            ->set('is_current', false)
            ->set('responsibilities_text', "Built APIs\nWrote tests")
            ->set('technologies_text', 'Laravel, PostgreSQL')
            ->call('save')
            ->assertHasNoErrors();

        $entry = Experience::firstWhere('company', 'Example Corp');
        $this->assertNotNull($entry);
        $this->assertCount(2, $entry->responsibilities);
        $this->assertSame(['Laravel', 'PostgreSQL'], $entry->technologies);
    }

    public function test_current_position_clears_end_date(): void
    {
        Livewire::test(Index::class)
            ->call('create')
            ->set('company', 'Current Co')
            ->set('position', 'Dev')
            ->set('start_date', '2024-01-01')
            ->set('end_date', '2024-06-01')
            ->set('is_current', true)
            ->call('save')
            ->assertHasNoErrors();

        $entry = Experience::firstWhere('company', 'Current Co');
        $this->assertNull($entry->end_date);
        $this->assertTrue($entry->is_current);
    }

    public function test_end_date_must_be_after_start_date(): void
    {
        Livewire::test(Index::class)
            ->call('create')
            ->set('company', 'Co')
            ->set('position', 'Dev')
            ->set('start_date', '2024-06-01')
            ->set('end_date', '2024-01-01')
            ->call('save')
            ->assertHasErrors(['end_date' => 'after_or_equal']);
    }
}
