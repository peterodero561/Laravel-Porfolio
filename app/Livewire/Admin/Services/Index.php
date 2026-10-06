<?php

namespace App\Livewire\Admin\Services;

use App\Models\Service;
use App\Services\AdminStatsService;
use Livewire\Component;

class Index extends Component
{
    public bool $showModal = false;

    public ?int $editingId = null;

    public string $title = '';

    public string $description = '';

    public string $icon = '';

    public string $technologies_text = '';

    public bool $published = true;

    public int $sort_order = 0;

    public function create(): void
    {
        $this->reset(['editingId', 'title', 'description', 'icon', 'technologies_text', 'published', 'sort_order']);
        $this->published = true;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function edit(string $id): void
    {
        $service = Service::findOrFail($id);
        $this->editingId = $service->id;
        $this->title = $service->title;
        $this->description = $service->description;
        $this->icon = $service->icon ?? '';
        $this->technologies_text = implode(', ', $service->technologies ?? []);
        $this->published = $service->published;
        $this->sort_order = $service->sort_order;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string', 'max:1000'],
            'icon' => ['nullable', 'string', 'max:80'],
            'technologies_text' => ['nullable', 'string', 'max:500'],
            'published' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
        ]);

        $data['technologies'] = collect(explode(',', $data['technologies_text'] ?? ''))
            ->map(fn ($t) => trim($t))->filter()->values()->all();

        unset($data['technologies_text']);

        if ($this->editingId) {
            Service::findOrFail($this->editingId)->update($data);
            $msg = 'Service updated.';
        } else {
            Service::create($data);
            $msg = 'Service created.';
        }

        AdminStatsService::forget();
        $this->showModal = false;
        $this->dispatch('toast', type: 'success', message: $msg);
    }

    public function togglePublished(string $id): void
    {
        $service = Service::findOrFail($id);
        $service->update(['published' => ! $service->published]);
        AdminStatsService::forget();
        $this->dispatch('toast', type: 'success', message: 'Publish state updated.');
    }

    public function delete(string $id): void
    {
        Service::findOrFail($id)->delete();
        AdminStatsService::forget();
        $this->dispatch('toast', type: 'success', message: 'Service deleted.');
    }

    public function render()
    {
        return view('livewire.admin.services.index', [
            'services' => Service::orderBy('sort_order')->orderBy('title')->get(),
        ])->layout('components.layouts.admin', ['title' => 'Services']);
    }
}
