@php
    $links = [
        ['label' => 'Projects', 'href' => route('projects.index')],
        ['label' => 'About',    'href' => route('about')],
        ['label' => 'Services', 'href' => route('services')],
        ['label' => 'Resume',   'href' => route('resume')],
    ];
@endphp

<header x-data="{ open: false }"
        class="sticky top-0 z-40 border-b border-line bg-ink-950/70 backdrop-blur-md">
    <div class="container-page flex h-16 items-center justify-between">
        <a href="{{ route('home') }}"
           class="flex items-center gap-2 font-semibold tracking-tight">
            <span class="inline-block h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
            <span>{{ config('app.name') }}</span>
        </a>

        <nav class="hidden items-center gap-1 md:flex" aria-label="Primary">
            @foreach ($links as $link)
                <a href="{{ $link['href'] }}"
                   @class([
                       'rounded-md px-3 py-2 text-sm transition',
                       'text-fg' => request()->url() === $link['href'],
                       'text-fg-muted hover:text-fg' => request()->url() !== $link['href'],
                   ])>
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="flex items-center gap-2">
            <a href="{{ route('contact') }}"
               class="hidden rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-brand-400 sm:inline-flex">
                Contact me
            </a>
            <button type="button"
                    class="rounded-md p-2 text-fg-muted transition hover:text-fg md:hidden"
                    @click="open = !open"
                    :aria-expanded="open.toString()"
                    aria-controls="mobile-nav"
                    aria-label="Toggle navigation">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 6h18M3 12h18M3 18h18" />
                </svg>
            </button>
        </div>
    </div>

    <div id="mobile-nav" x-show="open" x-cloak x-transition.opacity class="border-t border-line md:hidden">
        <nav class="container-page flex flex-col py-4" aria-label="Mobile">
            @foreach ($links as $link)
                <a href="{{ $link['href'] }}"
                   class="rounded-md px-3 py-3 text-sm text-fg-muted transition hover:bg-white/5 hover:text-fg">
                    {{ $link['label'] }}
                </a>
            @endforeach
            <a href="{{ route('contact') }}"
               class="mt-2 rounded-lg bg-brand-500 px-4 py-3 text-center text-sm font-medium text-white">
                Contact me
            </a>
        </nav>
    </div>
</header>