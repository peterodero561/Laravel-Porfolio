@props([
    'id' => null,
    'eyebrow' => null,
    'title' => null,
    'lead' => null,
    'align' => 'left',
])

<section @if($id) id="{{ $id }}" @endif class="py-20 sm:py-28">
    <div class="container-page">
        @if ($eyebrow || $title || $lead)
            <header @class([
                'mb-12 max-w-2xl',
                'mx-auto text-center' => $align === 'center',
            ])>
                @if ($eyebrow)
                    <p class="font-mono text-xs uppercase tracking-[0.2em] text-brand-400">
                        {{ $eyebrow }}
                    </p>
                @endif

                @if ($title)
                    <h2 class="mt-3 text-3xl font-semibold tracking-tight text-balance sm:text-4xl">
                        {{ $title }}
                    </h2>
                @endif

                @if ($lead)
                    <p class="mt-4 text-base leading-relaxed text-fg-muted">
                        {{ $lead }}
                    </p>
                @endif
            </header>
        @endif

        {{ $slot }}
    </div>
</section>