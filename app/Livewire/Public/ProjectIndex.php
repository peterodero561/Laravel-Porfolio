<?php

namespace App\Livewire\Public;

use App\Enums\ProjectCategory;
use App\Models\Project;
use App\Support\Seo;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProjectIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(as: 'category', except: 'all')]
    public string $category = 'all';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategory(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->category = 'all';
        $this->resetPage();
    }

    public function render()
    {
        $seo = Seo::make()
            ->title('Projects')
            ->description('Case studies of web, mobile, AI and automation projects.')
            ->canonical(route('projects.index'));

        $query = Project::query()
            ->select([
                'id', 'title', 'slug', 'short_description', 'thumbnail',
                'category', 'project_date', 'sort_order',
            ])
            ->with('technologies:id,name,slug')
            ->where('published', true);

        if ($this->category !== 'all') {
            $query->where('category', $this->category);
        }

        if ($this->search !== '') {
            $term = '%'.$this->search.'%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                    ->orWhere('short_description', 'like', $term);
            });
        }

        return view('livewire.public.project-index', [
            'projects' => $query
                ->orderBy('sort_order')
                ->orderByDesc('project_date')
                ->paginate(12),
            'categories' => ProjectCategory::cases(),
        ])->layout('components.layouts.public', ['seo' => $seo]);
    }
}
