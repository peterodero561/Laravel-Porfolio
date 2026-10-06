@php
    $resumeFile = $portfolio->setting('resume_file');
    $resumeUrl = $resumeFile ? \Illuminate\Support\Facades\Storage::disk('public')->url($resumeFile) : null;

    $seo = \App\Support\Seo::make()
        ->title('Resume')
        ->description('Download my resume.')
        ->canonical(route('resume'));
@endphp

<x-layouts.public :seo="$seo">
    <section class="border-b border-line">
        <div class="container-page py-16 sm:py-20">
            <p class="font-mono text-xs uppercase tracking-[0.25em] text-brand-400">Resume</p>
            <h1 class="mt-4 text-4xl font-semibold tracking-tight sm:text-5xl">Curriculum vitae</h1>
            <p class="mt-4 max-w-2xl text-fg-muted">
                A concise summary of my experience, skills and background.
            </p>

            @if ($resumeUrl)
                <div class="mt-8 flex flex-wrap gap-3">
                    <x-button :href="$resumeUrl" download size="lg">Download PDF</x-button>
                    <x-button :href="$resumeUrl" variant="secondary" size="lg" target="_blank" rel="noopener">
                        Open in new tab
                    </x-button>
                </div>
            @endif
        </div>
    </section>

    @if ($resumeUrl)
        <section class="container-page py-12">
            <div class="overflow-hidden rounded-xl border border-line bg-ink-900/70">
                <object data="{{ $resumeUrl }}" type="application/pdf" class="h-[80vh] w-full" aria-label="Resume PDF">
                    <p class="p-8 text-center text-sm text-fg-muted">
                        Your browser can't display the PDF inline.
                        <a href="{{ $resumeUrl }}" class="text-brand-400 hover:text-brand-300">Download it instead</a>.
                    </p>
                </object>
            </div>
        </section>
    @else
        <x-section>
            <p class="text-fg-muted">Resume file not yet uploaded. Add one from the admin panel.</p>
        </x-section>
    @endif
</x-layouts.public>