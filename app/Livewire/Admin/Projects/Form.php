<?php

namespace App\Livewire\Admin\Projects;

use App\Enums\ProjectCategory;
use App\Jobs\OptimizeProjectImage;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Technology;
use App\Services\AdminStatsService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public ?Project $project = null;

    public bool $isEditing = false;

    // Core
    public string $title = '';

    public string $slug = '';

    public string $short_description = '';

    public string $full_description = '';

    public string $category = 'web';

    public string $github_url = '';

    public string $live_url = '';

    public bool $featured = false;

    public bool $published = true;

    public int $sort_order = 0;

    public ?string $project_date = null;

    // Case study
    public string $problem = '';

    public string $solution = '';

    public string $features_text = '';

    public string $architecture = '';

    public string $technical_implementation = '';

    public string $challenges = '';

    public string $results = '';

    // Media
    public $thumbnail = null;

    public ?string $existingThumbnail = null;

    /** @var array<int, TemporaryUploadedFile> */
    public array $newImages = [];

    /** @var array<int, string> */
    public array $existingImages = [];

    // Technologies (comma-separated)
    public string $technologies_text = '';

    public function mount(?Project $project = null): void
    {
        if (! $project || ! $project->exists) {
            $this->published = true;

            return;
        }

        $this->isEditing = true;
        $this->project = $project->load(['technologies', 'images']);

        $this->title = $project->title;
        $this->slug = $project->slug;
        $this->short_description = $project->short_description;
        $this->full_description = $project->full_description ?? '';
        $this->category = $project->category->value;
        $this->github_url = $project->github_url ?? '';
        $this->live_url = $project->live_url ?? '';
        $this->featured = $project->featured;
        $this->published = $project->published;
        $this->sort_order = $project->sort_order;
        $this->project_date = $project->project_date?->format('Y-m-d');
        $this->problem = $project->problem ?? '';
        $this->solution = $project->solution ?? '';
        $this->features_text = implode("\n", $project->features ?? []);
        $this->architecture = $project->architecture ?? '';
        $this->technical_implementation = $project->technical_implementation ?? '';
        $this->challenges = $project->challenges ?? '';
        $this->results = $project->results ?? '';
        $this->technologies_text = $project->technologies->pluck('name')->implode(', ');
        $this->existingThumbnail = $project->thumbnail;
        $this->existingImages = $project->images->pluck('path', 'id')->all();
    }

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'slug' => [
                'required', 'string', 'max:180', 'regex:/^[a-z0-9-]+$/',
                Rule::unique('projects', 'slug')->ignore($this->project?->id),
            ],
            'short_description' => ['required', 'string', 'max:500'],
            'full_description' => ['nullable', 'string'],
            'category' => ['required', Rule::enum(ProjectCategory::class)],
            'github_url' => ['nullable', 'url', 'max:255'],
            'live_url' => ['nullable', 'url', 'max:255'],
            'featured' => ['boolean'],
            'published' => ['boolean'],
            'sort_order' => ['integer', 'min:0', 'max:100000'],
            'project_date' => ['nullable', 'date'],
            'problem' => ['nullable', 'string'],
            'solution' => ['nullable', 'string'],
            'features_text' => ['nullable', 'string'],
            'architecture' => ['nullable', 'string'],
            'technical_implementation' => ['nullable', 'string'],
            'challenges' => ['nullable', 'string'],
            'results' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'newImages' => ['array', 'max:20'],
            'newImages.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'technologies_text' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function updatedTitle(): void
    {
        if (blank($this->slug)) {
            $this->slug = Str::slug($this->title);
        }
    }

    public function removeExistingImage(string $id): void
    {
        if (! $this->project) {
            return;
        }

        $image = ProjectImage::where('project_id', $this->project->id)->find($id);

        if ($image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }

        $this->existingImages = $this->project->fresh()->images->pluck('path', 'id')->all();
        $this->dispatch('toast', type: 'success', message: 'Image removed.');
    }

    public function removeThumbnail(): void
    {
        if ($this->thumbnail) {
            $this->thumbnail = null;

            return;
        }

        if ($this->existingThumbnail && $this->project) {
            Storage::disk('public')->delete($this->existingThumbnail);
            $this->project->update(['thumbnail' => null]);
            $this->existingThumbnail = null;
            $this->dispatch('toast', type: 'success', message: 'Thumbnail removed.');
        }
    }

    public function save(): void
    {
        $data = $this->validate();

        $payload = [
            'title' => $data['title'],
            'slug' => $data['slug'],
            'short_description' => $data['short_description'],
            'full_description' => $data['full_description'] ?: null,
            'category' => $data['category'],
            'github_url' => $data['github_url'] ?: null,
            'live_url' => $data['live_url'] ?: null,
            'featured' => $data['featured'],
            'published' => $data['published'],
            'sort_order' => $data['sort_order'],
            'project_date' => $data['project_date'] ?: null,
            'problem' => $data['problem'] ?: null,
            'solution' => $data['solution'] ?: null,
            'features' => $this->parseLines($data['features_text'] ?? ''),
            'architecture' => $data['architecture'] ?: null,
            'technical_implementation' => $data['technical_implementation'] ?: null,
            'challenges' => $data['challenges'] ?: null,
            'results' => $data['results'] ?: null,
        ];

        if ($this->thumbnail) {
            if ($this->existingThumbnail) {
                Storage::disk('public')->delete($this->existingThumbnail);
            }
            $payload['thumbnail'] = $this->thumbnail->store('projects/thumbnails', 'public');
        }

        if ($this->isEditing) {
            $this->project->update($payload);
            $project = $this->project;
        } else {
            $project = Project::create($payload);
        }

        // Technologies — sync pivot
        $techIds = $this->resolveTechnologyIds($this->technologies_text);
        $project->technologies()->sync($techIds);

        // New gallery images
        foreach ($this->newImages as $i => $file) {
            $path = $file->store('projects/gallery', 'public');

            $image = $project->images()->create([
                'path' => $path,
                'alt' => $project->title,
                'sort_order' => $i,
            ]);

            OptimizeProjectImage::dispatch($image);
        }

        // Explicit invalidation: pivot writes don't fire Project::saved
        Project::forgetCaches();
        AdminStatsService::forget();

        $this->dispatch('toast', type: 'success', message: $this->isEditing ? 'Project updated.' : 'Project created.');

        $this->redirectRoute('admin.projects', navigate: true);
    }

    /**
     * @return array<int, int>
     */
    private function resolveTechnologyIds(string $text): array
    {
        return collect(explode(',', $text))
            ->map(fn ($name) => trim($name))
            ->filter()
            ->unique(fn ($name) => Str::lower($name))
            ->map(function (string $name) {
                return Technology::firstOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name],
                )->id;
            })
            ->values()
            ->all();
    }

    /**
     * @return array<int, string>
     */
    private function parseLines(string $text): array
    {
        return collect(explode("\n", $text))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    public function render()
    {
        return view('livewire.admin.projects.form', [
            'categories' => ProjectCategory::cases(),
        ])->layout('components.layouts.admin', [
            'title' => $this->isEditing ? 'Edit project' : 'New project',
        ]);
    }
}
