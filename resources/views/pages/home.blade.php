@php
    $settings = $portfolio->settings();
    $name = $settings['name'] ?? config('app.name');
    $title = $settings['professional_title'] ?? 'Software Developer';
    $bio = $settings['short_bio'] ?? null;

    $featured = $portfolio->featuredProjects(limit: 6);
    $skills = $portfolio->skillsByCategory();
    $experience = $portfolio->experience()->take(3);
    $services = $portfolio->services()->take(6);

    $seo = \App\Support\Seo::make()
        ->description($bio)
        ->canonical(route('home'))
        ->jsonLd(\App\Support\StructuredData::person($settings))
        ->jsonLd(\App\Support\StructuredData::website($settings));
@endphp

<x-layouts.public :seo="$seo">
    {{-- ─────────────── HERO ─────────────── --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-grid" aria-hidden="true"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-ink-950/60 to-ink-950" aria-hidden="true"></div>

        <div class="container-page relative grid gap-12 py-24 sm:py-32 lg:grid-cols-[1.15fr_1fr] lg:items-center">
            <div>
                <div class="flex items-center gap-2">
                    <span class="inline-block h-2 w-2 rounded-full bg-emerald-400"></span>
                    <span class="font-mono text-xs uppercase tracking-[0.25em] text-fg-muted">
                        {{ $title }}
                    </span>
                </div>

                <h1 class="mt-6 max-w-3xl text-4xl font-semibold leading-[1.05] tracking-tight text-balance sm:text-6xl">
                    {{ $bio ?? 'I build modern digital products that solve real-world problems.' }}
                </h1>

                <p class="mt-6 max-w-xl text-base text-fg-muted sm:text-lg">
                    Laravel · Flutter · AI · APIs · IoT
                </p>

                <div class="mt-10 flex flex-wrap items-center gap-3">
                    <x-button href="{{ route('projects.index') }}" size="lg">
                        Explore my work
                    </x-button>
                    <x-button href="{{ route('contact') }}" variant="secondary" size="lg">
                        Contact me
                    </x-button>
                </div>
            </div>

            <x-terminal :name="$name" />
        </div>
    </section>

    {{-- ─────────────── SKILLS ─────────────── --}}
    @if (!empty($skills))
        <x-section
            id="skills"
            eyebrow="Stack"
            title="Technologies I work with"
            lead="Backend, mobile, AI and infrastructure — used in production, not just tutorials."
        >
            <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($skills as $category => $items)
                    @php $enum = \App\Enums\SkillCategory::from($category); @endphp
                    <div>
                        <h3 class="font-mono text-xs uppercase tracking-[0.2em] text-brand-400">
                            {{ $enum->label() }}
                        </h3>
                        <div class="mt-4 flex flex-wrap gap-1.5">
                            @foreach ($items as $skill)
                                <x-badge>{{ $skill->name }}</x-badge>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </x-section>
    @endif

    {{-- ─────────────── FEATURED PROJECTS ─────────────── --}}
    @if ($featured->isNotEmpty())
        <x-section
            id="work"
            eyebrow="Selected work"
            title="Featured projects"
            lead="A few case studies that show how I approach real products."
        >
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($featured as $project)
                    <x-project-card :project="$project" />
                @endforeach
            </div>

            <div class="mt-10">
                <x-button href="{{ route('projects.index') }}" variant="secondary">
                    View all projects →
                </x-button>
            </div>
        </x-section>
    @endif

    {{-- ─────────────── CAPABILITIES ─────────────── --}}
    <x-section
        id="capabilities"
        eyebrow="Engineering"
        title="What I actually build"
        lead="End-to-end ownership: schema → API → UI → deployment → monitoring."
    >
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            <x-capability label="Web" description="Full-stack Laravel applications with Livewire, Tailwind and clean domain boundaries." />
            <x-capability label="Mobile" description="Flutter apps with native-feeling UX, offline sync and push notifications." />
            <x-capability label="AI" description="LLM-powered search, extraction, ranking and summarisation in production pipelines." />
            <x-capability label="Backend" description="APIs, queues, caching, background jobs, database modelling and performance tuning." />
        </div>
    </x-section>

    {{-- ─────────────── EXPERIENCE ─────────────── --}}
    @if ($experience->isNotEmpty())
        <x-section
            id="experience"
            eyebrow="Journey"
            title="Experience"
        >
            <ol class="max-w-3xl">
                @foreach ($experience as $entry)
                    <x-experience-item :experience="$entry" :last="$loop->last" />
                @endforeach
            </ol>
        </x-section>
    @endif

    {{-- ─────────────── SERVICES ─────────────── --}}
    @if ($services->isNotEmpty())
        <x-section
            id="services"
            eyebrow="Services"
            title="How I can help"
            lead="Whether you need a full product or a specific piece, here's where I plug in."
        >
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($services as $service)
                    <x-service-card :service="$service" />
                @endforeach
            </div>
        </x-section>
    @endif

    {{-- ─────────────── CTA ─────────────── --}}
    <section class="py-20 sm:py-28">
        <div class="container-page">
            <div class="relative overflow-hidden rounded-2xl border border-line bg-ink-900/70 px-8 py-14 sm:px-14">
                <div class="absolute inset-0 bg-grid opacity-50" aria-hidden="true"></div>
                <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                    <div class="max-w-xl">
                        <h2 class="text-2xl font-semibold tracking-tight sm:text-3xl text-balance">
                            Have a project in mind?
                        </h2>
                        <p class="mt-3 text-fg-muted">
                            Tell me what you're building — I'll tell you how I'd approach it.
                        </p>
                    </div>
                    <x-button href="{{ route('contact') }}" size="lg">
                        Get in touch
                    </x-button>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>