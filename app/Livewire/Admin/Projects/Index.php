<?php

namespace App\Livewire\Admin\Projects;

use App\Models\Project;
use App\Services\AdminStatsService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(as: 'status', except: 'all')]
    public string $status = 'all'; // all | published | draft | featured

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function togglePublished(string $id): void
    {
        $project = Project::findOrFail($id);
        $project->update(['published' => ! $project->published]);
        AdminStatsService::forget();
        $this->dispatch('toast', type: 'success', message: 'Publish state updated.');
    }

    public function toggleFeatured(string $id): void
    {
        $project = Project::findOrFail($id);
        $project->update(['featured' => ! $project->featured]);
        AdminStatsService::forget();
        $this->dispatch('toast', type: 'success', message: 'Featured state updated.');
    }

    public function delete(string $id): void
    {
        Project::findOrFail($id)->delete();
        AdminStatsService::forget();
        $this->dispatch('toast', type: 'success', message: 'Project deleted.');
    }

    public function render()
    {
        $query = Project::query()
            ->select(['id', 'title', 'slug', 'category', 'thumbnail', 'featured', 'published', 'sort_order', 'project_date'])
            ->withCount('images');

        if ($this->search !== '') {
            $query->where('title', 'like', '%'.$this->search.'%');
        }

        match ($this->status) {
            'published' => $query->where('published', true),
            'draft' => $query->where('published', false),
            'featured' => $query->where('featured', true),
            default => null,
        };

        return view('livewire.admin.projects.index', [
            'projects' => $query->orderBy('sort_order')->orderByDesc('updated_at')->paginate(15),
        ])->layout('components.layouts.admin', ['title' => 'Projects']);
    }
}
