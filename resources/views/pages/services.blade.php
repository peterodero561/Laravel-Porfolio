@php
    $services = $portfolio->services();

    $seo = \App\Support\Seo::make()
        ->title('Services')
        ->description('Development services: web, mobile, APIs, AI integration, workflow automation.')
        ->canonical(route('services'));
@endphp

<x-layouts.public :seo="$seo">
    <section class="border-b border-line">
        <div class="container-page py-16 sm:py-24">
            <p class="font-mono text-xs uppercase tracking-[0.25em] text-brand-400">Services</p>
            <h1 class="mt-4 max-w-3xl text-4xl font-semibold tracking-tight text-balance sm:text-5xl">
                How I can help you ship.
            </h1>
            <p class="mt-6 max-w-2xl text-lg text-fg-muted">
                Whether you need a full product or one specific piece, these are the ways I typically plug in.
            </p>
        </div>
    </section>

    <x-section>
        @if ($services->isEmpty())
            <p class="text-fg-muted">Services will appear here once published from the admin panel.</p>
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($services as $service)
                    <x-service-card :service="$service" />
                @endforeach
            </div>
        @endif
    </x-section>

    <section class="container-page pb-20">
        <div class="rounded-2xl border border-line bg-ink-900/70 p-10 text-center sm:p-14">
            <h2 class="text-2xl font-semibold tracking-tight">Not sure which one fits?</h2>
            <p class="mt-3 text-fg-muted">Describe your problem — I'll suggest the approach.</p>
            <div class="mt-6">
                <x-button href="{{ route('contact') }}" size="lg">Talk to me</x-button>
            </div>
        </div>
    </section>
</x-layouts.public>