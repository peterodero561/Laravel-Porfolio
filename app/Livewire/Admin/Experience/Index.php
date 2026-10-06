<?php

namespace App\Livewire\Admin\Experience;

use App\Models\Experience;
use App\Services\AdminStatsService;
use Livewire\Component;

class Index extends Component
{
    public bool $showModal = false;

    public ?int $editingId = null;

    public string $company = '';

    public string $position = '';

    public string $location = '';

    public string $start_date = '';

    public ?string $end_date = null;

    public bool $is_current = false;

    public string $description = '';

    public string $responsibilities_text = '';

    public string $technologies_text = '';

    public int $sort_order = 0;

    public function create(): void
    {
        $this->reset([
            'editingId', 'company', 'position', 'location', 'start_date',
            'end_date', 'is_current', 'description', 'responsibilities_text',
            'technologies_text', 'sort_order',
        ]);
        $this->resetValidation();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $exp = Experience::findOrFail($id);
        $this->editingId = $exp->id;
        $this->company = $exp->company;
        $this->position = $exp->position;
        $this->location = $exp->location ?? '';
        $this->start_date = $exp->start_date->format('Y-m-d');
        $this->end_date = $exp->end_date?->format('Y-m-d');
        $this->is_current = $exp->is_current;
        $this->description = $exp->description ?? '';
        $this->responsibilities_text = implode("\n", $exp->responsibilities ?? []);
        $this->technologies_text = implode(', ', $exp->technologies ?? []);
        $this->sort_order = $exp->sort_order;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate([
            'company' => ['required', 'string', 'max:150'],
            'position' => ['required', 'string', 'max:150'],
            'location' => ['nullable', 'string', 'max:150'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_current' => ['boolean'],
            'description' => ['nullable', 'string'],
            'responsibilities_text' => ['nullable', 'string'],
            'technologies_text' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['integer', 'min:0'],
        ]);

        if ($data['is_current']) {
            $data['end_date'] = null;
        }

        $data['responsibilities'] = $this->parseLines($data['responsibilities_text'] ?? '');
        $data['technologies'] = collect(explode(',', $data['technologies_text'] ?? ''))
            ->map(fn ($t) => trim($t))->filter()->values()->all();

        unset($data['responsibilities_text'], $data['technologies_text']);

        if ($this->editingId) {
            Experience::findOrFail($this->editingId)->update($data);
            $msg = 'Experience updated.';
        } else {
            Experience::create($data);
            $msg = 'Experience created.';
        }

        AdminStatsService::forget();
        $this->showModal = false;
        $this->dispatch('toast', type: 'success', message: $msg);
    }

    public function delete(int $id): void
    {
        Experience::findOrFail($id)->delete();
        AdminStatsService::forget();
        $this->dispatch('toast', type: 'success', message: 'Experience deleted.');
    }

    private function parseLines(string $text): array
    {
        return collect(explode("\n", $text))->map(fn ($l) => trim($l))->filter()->values()->all();
    }

    public function render()
    {
        return view('livewire.admin.experience.index', [
            'entries' => Experience::orderByDesc('is_current')->orderByDesc('start_date')->orderBy('sort_order')->get(),
        ])->layout('components.layouts.admin', ['title' => 'Experience']);
    }
}
