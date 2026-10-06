@php
    $settings = $portfolio->settings();
    $experience = $portfolio->experience();
    $skills = $portfolio->skillsByCategory();

    $seo = \App\Support\Seo::make()
        ->title('About')
        ->description($settings['short_bio'] ?? 'About me.')
        ->canonical(route('about'))
        ->jsonLd(\App\Support\StructuredData::person($settings));
@endphp

<x-layouts.public :seo="$seo">
    <section class="border-b border-line">
        <div class="container-page py-16 sm:py-24">
            <div class="grid gap-12 lg:grid-cols-[1fr_320px] lg:items-start lg:gap-20">
                <div>
                    <p class="font-mono text-xs uppercase tracking-[0.25em] text-brand-400">About</p>
                    <h1 class="mt-4 text-4xl font-semibold tracking-tight text-balance sm:text-5xl">
                        {{ $settings['name'] ?? config('app.name') }}
                    </h1>
                    <p class="mt-6 max-w-2xl text-lg leading-relaxed text-fg-muted">
                        {{ $settings['short_bio'] ?? 'Software developer building useful things.' }}
                    </p>

                    <div class="prose prose-invert mt-8 max-w-2xl text-fg-muted">
                        <p>
                            I design and ship full-stack products — mostly Laravel on the backend, Flutter on mobile,
                            and increasingly LLM-powered features on top. I care about clean domain models, fast
                            queries, and interfaces that feel obvious to use.
                        </p>
                        <p>
                            My work tends to sit at the intersection of web, mobile and automation — property
                            platforms, dashboards, and pipelines that connect the tools a business already runs on.
                        </p>
                    </div>
                </div>

                @if ($settings['profile_image'] ?? null)
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($settings['profile_image']) }}"
                         alt="{{ $settings['name'] ?? 'Profile' }}"
                         width="480" height="480"
                         class="aspect-square w-full rounded-2xl border border-line object-cover">
                @endif
            </div>
        </div>
    </section>

    @if (!empty($skills))
        <x-section eyebrow="Stack" title="Tools I use day to day">
            <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($skills as $category => $items)
                    @php $enum = \App\Enums\SkillCategory::from($category); @endphp
                    <div>
                        <h3 class="font-mono text-xs uppercase tracking-[0.2em] text-brand-400">{{ $enum->label() }}</h3>
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

    @if ($experience->isNotEmpty())
        <x-section eyebrow="Journey" title="Experience">
            <ol class="max-w-3xl">
                @foreach ($experience as $entry)
                    <x-experience-item :experience="$entry" :last="$loop->last" />
                @endforeach
            </ol>
        </x-section>
    @endif

    <section class="container-page py-20">
        <div class="rounded-2xl border border-line bg-ink-900/70 p-10 text-center sm:p-14">
            <h2 class="text-2xl font-semibold tracking-tight">Let's work together</h2>
            <p class="mt-3 text-fg-muted">I'm open to new projects and collaborations.</p>
            <div class="mt-6 flex flex-wrap justify-center gap-3">
                <x-button href="{{ route('contact') }}" size="lg">Get in touch</x-button>
                <x-button href="{{ route('resume') }}" variant="secondary" size="lg">View resume</x-button>
            </div>
        </div>
    </section>
</x-layouts.public>