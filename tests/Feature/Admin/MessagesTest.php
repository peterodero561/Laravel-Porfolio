<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Messages\Index;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MessagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs($this->admin());
    }

    public function test_index_lists_messages(): void
    {
        ContactMessage::factory()->count(3)->create();

        $this->get('/admin/messages')->assertOk()->assertSee('Messages');
    }

    public function test_opening_a_message_marks_it_as_read(): void
    {
        $message = ContactMessage::factory()->unread()->create();

        Livewire::test(Index::class)->call('open', $message->id);

        $this->assertNotNull($message->fresh()->read_at);
    }

    public function test_admin_can_mark_a_message_as_unread(): void
    {
        $message = ContactMessage::factory()->read()->create();

        Livewire::test(Index::class)->call('markUnread', $message->id);

        $this->assertNull($message->fresh()->read_at);
    }

    public function test_filter_by_unread(): void
    {
        ContactMessage::factory()->count(2)->unread()->create();
        ContactMessage::factory()->count(3)->read()->create();

        Livewire::test(Index::class)
            ->set('filter', 'unread')
            ->assertViewHas('messages', fn ($p) => $p->total() === 2);
    }

    public function test_admin_can_delete_a_message(): void
    {
        $message = ContactMessage::factory()->create();

        Livewire::test(Index::class)->call('delete', $message->id);

        $this->assertDatabaseMissing('contact_messages', ['id' => $message->id]);
    }
}
