<div>
    <section class="border-b border-line">
        <div class="container-page py-16 sm:py-20">
            <p class="font-mono text-xs uppercase tracking-[0.25em] text-brand-400">Work</p>
            <h1 class="mt-4 text-4xl font-semibold tracking-tight text-balance sm:text-5xl">
                Projects
            </h1>
            <p class="mt-4 max-w-2xl text-fg-muted">
                Real products, real constraints. Each case study covers the problem, the approach and the outcome.
            </p>
        </div>
    </section>

    <section class="container-page py-12">
        {{-- Filters --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-wrap gap-1.5" role="tablist" aria-label="Filter by category">
                <button type="button"
                        wire:click="$set('category', 'all')"
                        @class([
                            'rounded-full border px-3.5 py-1.5 text-xs font-medium transition',
                            'border-brand-500/40 bg-brand-500/10 text-brand-300' => $category === 'all',
                            'border-line bg-white/5 text-fg-muted hover:text-fg' => $category !== 'all',
                        ])
                        aria-pressed="{{ $category === 'all' ? 'true' : 'false' }}">
                    All
                </button>

                @foreach ($categories as $cat)
                    <button type="button"
                            wire:click="$set('category', '{{ $cat->value }}')"
                            @class([
                                'rounded-full border px-3.5 py-1.5 text-xs font-medium transition',
                                'border-brand-500/40 bg-brand-500/10 text-brand-300' => $category === $cat->value,
                                'border-line bg-white/5 text-fg-muted hover:text-fg' => $category !== $cat->value,
                            ])
                            aria-pressed="{{ $category === $cat->value ? 'true' : 'false' }}">
                        {{ $cat->label() }}
                    </button>
                @endforeach
            </div>

            <div class="relative w-full sm:w-64">
                <label for="project-search" class="sr-only">Search projects</label>
                <input id="project-search"
                       type="search"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Search projects…"
                       class="w-full rounded-lg border border-line bg-white/5 px-3.5 py-2 text-sm text-fg placeholder:text-fg-dim focus:border-brand-500 focus:outline-none">
            </div>
        </div>

        {{-- Results --}}
        <div class="mt-10" wire:loading.class.delay="opacity-60" wire:target="search, category, gotoPage, nextPage, previousPage">
            @if ($projects->isEmpty())
                <div class="rounded-xl border border-line bg-white/[0.02] p-12 text-center">
                    <p class="text-fg-muted">No projects match your filters.</p>
                    <button type="button"
                            wire:click="clearFilters"
                            class="mt-4 text-sm font-medium text-brand-400 hover:text-brand-300">
                        Clear filters
                    </button>
                </div>
            @else
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($projects as $project)
                        <x-project-card :project="$project" :key="$project->id" />
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $projects->links() }}
                </div>
            @endif
        </div>
    </section>
</div>