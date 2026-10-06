<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Projects</h1>
            <p class="mt-1 text-sm text-fg-muted">Manage case studies, media and feature flags.</p>
        </div>
        <x-button :href="route('admin.projects.create')">New project</x-button>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <div class="flex flex-wrap gap-1.5">
            @foreach (['all' => 'All', 'published' => 'Published', 'draft' => 'Draft', 'featured' => 'Featured'] as $value => $label)
                <button type="button"
                        wire:click="$set('status', '{{ $value }}')"
                        @class([
                            'rounded-full border px-3 py-1.5 text-xs',
                            'border-brand-500/40 bg-brand-500/10 text-brand-300' => $status === $value,
                            'border-line bg-white/5 text-fg-muted hover:text-fg' => $status !== $value,
                        ])>{{ $label }}</button>
            @endforeach
        </div>

        <div class="relative ml-auto w-full sm:w-64">
            <label for="q" class="sr-only">Search projects</label>
            <input id="q" type="search" wire:model.live.debounce.300ms="search"
                   placeholder="Search…"
                   class="w-full rounded-lg border border-line bg-white/5 px-3.5 py-2 text-sm text-fg placeholder:text-fg-dim focus:border-brand-500 focus:outline-none">
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-line" wire:loading.class.delay="opacity-60"
         wire:target="search, status, gotoPage, nextPage, previousPage, togglePublished, toggleFeatured, delete">
        <table class="w-full text-sm">
            <thead class="border-b border-line bg-white/[0.02] text-left text-xs uppercase tracking-wider text-fg-dim">
                <tr>
                    <th class="px-4 py-3 font-medium">Title</th>
                    <th class="hidden px-4 py-3 font-medium sm:table-cell">Category</th>
                    <th class="hidden px-4 py-3 font-medium md:table-cell">Date</th>
                    <th class="px-4 py-3 font-medium">State</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @forelse ($projects as $project)
                    <tr wire:key="project-{{ $project->id }}">
                        <td class="px-4 py-3">
                            <div class="font-medium">{{ $project->title }}</div>
                            <div class="text-xs text-fg-dim">{{ $project->images_count }} images</div>
                        </td>
                        <td class="hidden px-4 py-3 sm:table-cell">
                            <x-badge variant="brand">{{ $project->category->label() }}</x-badge>
                        </td>
                        <td class="hidden px-4 py-3 text-fg-muted md:table-cell">
                            {{ $project->project_date?->format('M Y') ?? '—' }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1.5">
                                <button type="button" wire:click="togglePublished({{ $project->id }})"
                                        @class([
                                            'rounded-full border px-2 py-0.5 text-[11px]',
                                            'border-emerald-500/40 bg-emerald-500/10 text-emerald-400' => $project->published,
                                            'border-line bg-white/5 text-fg-dim' => ! $project->published,
                                        ])>
                                    {{ $project->published ? 'Published' : 'Draft' }}
                                </button>
                                <button type="button" wire:click="toggleFeatured({{ $project->id }})"
                                        @class([
                                            'rounded-full border px-2 py-0.5 text-[11px]',
                                            'border-cyan-500/40 bg-cyan-500/10 text-cyan-400' => $project->featured,
                                            'border-line bg-white/5 text-fg-dim' => ! $project->featured,
                                        ])>
                                    {{ $project->featured ? 'Featured' : 'Not featured' }}
                                </button>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.projects.edit', $project) }}"
                                   class="text-xs text-brand-400 hover:text-brand-300">Edit</a>
                                <button type="button"
                                        wire:click="delete({{ $project->id }})"
                                        wire:confirm="Delete this project? This cannot be undone."
                                        class="text-xs text-red-400 hover:text-red-300">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-fg-muted">
                            No projects yet. <a href="{{ route('admin.projects.create') }}" class="text-brand-400 hover:text-brand-300">Create one →</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $projects->links() }}</div>
</div>