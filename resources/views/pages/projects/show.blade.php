@php
    $settings = $portfolio->settings();
    $thumb = $project->thumbnail
        ? url(\Illuminate\Support\Facades\Storage::disk('public')->url($project->thumbnail))
        : null;

    $seo = \App\Support\Seo::make()
        ->title($project->title)
        ->description($project->short_description)
        ->canonical(route('projects.show', $project))
        ->type('article')
        ->image($thumb, $project->title)
        ->jsonLd(\App\Support\StructuredData::softwareApplication($project, $settings))
        ->jsonLd(\App\Support\StructuredData::breadcrumb([
            ['name' => 'Home', 'url' => route('home')],
            ['name' => 'Projects', 'url' => route('projects.index')],
            ['name' => $project->title, 'url' => route('projects.show', $project)],
        ]));
@endphp

<x-layouts.public :seo="$seo">
    <article>
        {{-- ─────────────── HEADER ─────────────── --}}
        <header class="border-b border-line">
            <div class="container-page py-16 sm:py-20">
                <nav aria-label="Breadcrumb" class="text-xs text-fg-dim">
                    <a href="{{ route('projects.index') }}" class="hover:text-fg">Projects</a>
                    <span class="mx-2">/</span>
                    <span class="text-fg-muted">{{ $project->title }}</span>
                </nav>

                <div class="mt-6 flex flex-wrap items-center gap-2">
                    <x-badge variant="brand">{{ $project->category->label() }}</x-badge>
                    @if ($project->project_date)
                        <span class="text-xs text-fg-dim">{{ $project->project_date->format('F Y') }}</span>
                    @endif
                </div>

                <h1 class="mt-4 max-w-3xl text-4xl font-semibold tracking-tight text-balance sm:text-5xl">
                    {{ $project->title }}
                </h1>

                <p class="mt-4 max-w-2xl text-lg text-fg-muted">
                    {{ $project->short_description }}
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-3">
                    @if ($project->live_url)
                        <x-button href="{{ $project->live_url }}" target="_blank" rel="noopener">
                            Live demo
                        </x-button>
                    @endif
                    @if ($project->github_url)
                        <x-button href="{{ $project->github_url }}" variant="secondary" target="_blank" rel="noopener">
                            View on GitHub
                        </x-button>
                    @endif
                </div>

                @if ($project->technologies->isNotEmpty())
                    <div class="mt-8 flex flex-wrap gap-1.5">
                        @foreach ($project->technologies as $tech)
                            <x-badge>{{ $tech->name }}</x-badge>
                        @endforeach
                    </div>
                @endif
            </div>
        </header>

        {{-- ─────────────── HERO IMAGE ─────────────── --}}
        @if ($thumb)
            <div class="container-page mt-12">
                <div class="overflow-hidden rounded-xl border border-line">
                    {{-- First visible image — do not lazy load (LCP) --}}
                    <img src="{{ $thumb }}"
                         alt="{{ $project->title }}"
                         width="1280" height="800"
                         fetchpriority="high"
                         decoding="sync"
                         class="h-auto w-full object-cover">
                </div>
            </div>
        @endif

        {{-- ─────────────── BODY ─────────────── --}}
        <div class="container-page grid gap-16 py-16 lg:grid-cols-[1fr_280px] lg:gap-20">
            <div class="min-w-0 space-y-14">
                @if ($project->full_description)
                    <section>
                        <h2 class="text-sm font-mono uppercase tracking-[0.2em] text-brand-400">Overview</h2>
                        <div class="prose prose-invert mt-4 max-w-none text-fg-muted">
                            {!! nl2br(e($project->full_description)) !!}
                        </div>
                    </section>
                @endif

                @if ($project->problem || $project->solution)
                    <section class="grid gap-8 sm:grid-cols-2">
                        @if ($project->problem)
                            <div>
                                <h2 class="text-sm font-mono uppercase tracking-[0.2em] text-brand-400">Problem</h2>
                                <p class="mt-4 text-fg-muted leading-relaxed">{{ $project->problem }}</p>
                            </div>
                        @endif
                        @if ($project->solution)
                            <div>
                                <h2 class="text-sm font-mono uppercase tracking-[0.2em] text-emerald-400">Solution</h2>
                                <p class="mt-4 text-fg-muted leading-relaxed">{{ $project->solution }}</p>
                            </div>
                        @endif
                    </section>
                @endif

                @if (!empty($project->features))
                    <section>
                        <h2 class="text-sm font-mono uppercase tracking-[0.2em] text-brand-400">Features</h2>
                        <ul class="mt-4 grid gap-3 sm:grid-cols-2">
                            @foreach ($project->features as $feature)
                                <li class="flex gap-3 rounded-lg border border-line bg-white/[0.02] p-4 text-sm text-fg-muted">
                                    <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-400" aria-hidden="true"></span>
                                    <span>{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @if ($project->architecture)
                    <section>
                        <h2 class="text-sm font-mono uppercase tracking-[0.2em] text-brand-400">Architecture</h2>
                        <pre class="mt-4 overflow-x-auto rounded-xl border border-line bg-ink-900/70 p-6 font-mono text-xs leading-relaxed text-fg-muted">{{ $project->architecture }}</pre>
                    </section>
                @endif

                @if ($project->technical_implementation)
                    <section>
                        <h2 class="text-sm font-mono uppercase tracking-[0.2em] text-brand-400">Technical implementation</h2>
                        <p class="mt-4 text-fg-muted leading-relaxed">{{ $project->technical_implementation }}</p>
                    </section>
                @endif

                @if ($project->challenges)
                    <section>
                        <h2 class="text-sm font-mono uppercase tracking-[0.2em] text-brand-400">Challenges</h2>
                        <p class="mt-4 text-fg-muted leading-relaxed">{{ $project->challenges }}</p>
                    </section>
                @endif

                @if ($project->results)
                    <section>
                        <h2 class="text-sm font-mono uppercase tracking-[0.2em] text-emerald-400">Results</h2>
                        <p class="mt-4 text-fg-muted leading-relaxed">{{ $project->results }}</p>
                    </section>
                @endif

                @if ($project->images->isNotEmpty())
                    <section>
                        <h2 class="text-sm font-mono uppercase tracking-[0.2em] text-brand-400">Gallery</h2>
                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            @foreach ($project->images as $image)
                                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($image->originalPath()) }}"
                                    target="_blank" rel="noopener"
                                   class="group block overflow-hidden rounded-xl border border-line">
                                    <img src="{{ $image->mediumUrl() }}"
                                     srcset="{{ $image->thumbUrl() }} 480w, {{ $image->mediumUrl() }} 1200w"
                                     sizes="(min-width: 640px) 50vw, 100vw"
                                     alt="{{ $image->alt ?? $project->title }}"
                                     loading="lazy"
                                     decoding="async"
                                     width="1200" height="750"
                                     class="h-auto w-full object-cover transition duration-500 group-hover:scale-[1.02]">
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            {{-- Sidebar --}}
            <aside class="space-y-8 lg:sticky lg:top-24 lg:self-start">
                <div>
                    <p class="text-xs font-mono uppercase tracking-[0.2em] text-fg-dim">Category</p>
                    <p class="mt-2 text-sm">{{ $project->category->label() }}</p>
                </div>

                @if ($project->project_date)
                    <div>
                        <p class="text-xs font-mono uppercase tracking-[0.2em] text-fg-dim">Date</p>
                        <p class="mt-2 text-sm">{{ $project->project_date->format('F Y') }}</p>
                    </div>
                @endif

                @if ($project->technologies->isNotEmpty())
                    <div>
                        <p class="text-xs font-mono uppercase tracking-[0.2em] text-fg-dim">Stack</p>
                        <div class="mt-2 flex flex-wrap gap-1.5">
                            @foreach ($project->technologies as $tech)
                                <x-badge>{{ $tech->name }}</x-badge>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="border-t border-line pt-6">
                    <a href="{{ route('projects.index') }}" class="text-sm text-fg-muted hover:text-fg">
                        ← All projects
                    </a>
                </div>
            </aside>
        </div>

        {{-- ─────────────── CTA ─────────────── --}}
        <section class="container-page pb-20">
            <div class="rounded-2xl border border-line bg-ink-900/70 p-10 text-center sm:p-14">
                <h2 class="text-2xl font-semibold tracking-tight">Interested in something similar?</h2>
                <p class="mt-3 text-fg-muted">Let's talk about your project.</p>
                <div class="mt-6">
                    <x-button href="{{ route('contact') }}" size="lg">Get in touch</x-button>
                </div>
            </div>
        </section>
    </article>
</x-layouts.public>