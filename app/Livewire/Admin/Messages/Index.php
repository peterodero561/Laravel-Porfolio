<?php

namespace App\Livewire\Admin\Messages;

use App\Models\ContactMessage;
use App\Services\AdminStatsService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url(as: 'filter', except: 'all')]
    public string $filter = 'all'; // all | unread | read

    public ?string $viewingId = null;

    public function updatingFilter(): void
    {
        $this->resetPage();
    }

    public function open(string $id): void
    {
        $message = ContactMessage::findOrFail($id);

        if ($message->read_at === null) {
            $message->markAsRead();
            AdminStatsService::forget();
        }

        $this->viewingId = $message->id;
    }

    public function close(): void
    {
        $this->viewingId = null;
    }

    public function markUnread(string $id): void
    {
        ContactMessage::findOrFail($id)->forceFill(['read_at' => null])->save();
        AdminStatsService::forget();
        $this->viewingId = null;
        $this->dispatch('toast', type: 'success', message: 'Marked as unread.');
    }

    public function delete(string $id): void
    {
        ContactMessage::findOrFail($id)->delete();
        AdminStatsService::forget();
        $this->viewingId = null;
        $this->dispatch('toast', type: 'success', message: 'Message deleted.');
    }

    public function render()
    {
        $query = ContactMessage::query()->latest();

        match ($this->filter) {
            'unread' => $query->whereNull('read_at'),
            'read' => $query->whereNotNull('read_at'),
            default => null,
        };

        return view('livewire.admin.messages.index', [
            'messages' => $query->paginate(20),
            'viewing' => $this->viewingId ? ContactMessage::find($this->viewingId) : null,
        ])->layout('components.layouts.admin', ['title' => 'Messages']);
    }
}
