<?php

namespace App\Livewire\Admin\Skills;

use App\Enums\SkillCategory;
use App\Models\Skill;
use App\Services\AdminStatsService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url(as: 'q', except: '')]
    public string $search = '';

    public bool $showModal = false;

    public ?string $editingId = null;

    public string $name = '';

    public string $category = 'backend';

    public string $icon = '';

    public bool $published = true;

    public int $sort_order = 0;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->reset(['editingId', 'name', 'category', 'icon', 'published', 'sort_order']);
        $this->category = 'backend';
        $this->published = true;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function edit(string $id): void
    {
        $skill = Skill::findOrFail($id);
        $this->editingId = $skill->id;
        $this->name = $skill->name;
        $this->category = $skill->category->value;
        $this->icon = $skill->icon ?? '';
        $this->published = $skill->published;
        $this->sort_order = $skill->sort_order;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:80'],
            'category' => ['required', 'string'],
            'icon' => ['nullable', 'string', 'max:80'],
            'published' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
        ]);

        if ($this->editingId) {
            Skill::findOrFail($this->editingId)->update($data);
            $msg = 'Skill updated.';
        } else {
            Skill::create($data);
            $msg = 'Skill created.';
        }

        AdminStatsService::forget();
        $this->showModal = false;
        $this->dispatch('toast', type: 'success', message: $msg);
    }

    public function delete(string $id): void
    {
        Skill::findOrFail($id)->delete();
        AdminStatsService::forget();
        $this->dispatch('toast', type: 'success', message: 'Skill deleted.');
    }

    public function render()
    {
        return view('livewire.admin.skills.index', [
            'skills' => Skill::query()
                ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
                ->orderBy('category')->orderBy('sort_order')->orderBy('name')
                ->paginate(30),
            'categories' => SkillCategory::cases(),
        ])->layout('components.layouts.admin', ['title' => 'Skills']);
    }
}
