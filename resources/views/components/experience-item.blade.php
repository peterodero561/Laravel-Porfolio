@props(['experience', 'last' => false])

<li class="relative pl-8 sm:pl-10">
    @unless ($last)
        <span class="absolute left-[7px] top-2 bottom-0 w-px bg-line" aria-hidden="true"></span>
    @endunless

    <span class="absolute left-0 top-1.5 h-4 w-4 rounded-full border-2 border-brand-500 bg-ink-950" aria-hidden="true"></span>

    <div class="pb-10">
        <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
            <h3 class="text-base font-semibold tracking-tight">{{ $experience->position }}</h3>
            <span class="text-sm text-brand-400">{{ $experience->company }}</span>
        </div>

        <p class="mt-1 text-xs text-fg-dim">
            {{ $experience->start_date->format('M Y') }} —
            {{ $experience->is_current ? 'Present' : $experience->end_date?->format('M Y') }}
            @if ($experience->location) · {{ $experience->location }} @endif
        </p>

        @if ($experience->description)
            <p class="mt-3 text-sm leading-relaxed text-fg-muted">{{ $experience->description }}</p>
        @endif

        @if (!empty($experience->responsibilities))
            <ul class="mt-3 space-y-1.5 text-sm text-fg-muted">
                @foreach ($experience->responsibilities as $item)
                    <li class="flex gap-2">
                        <span class="mt-2 h-1 w-1 shrink-0 rounded-full bg-fg-dim" aria-hidden="true"></span>
                        <span>{{ $item }}</span>
                    </li>
                @endforeach
            </ul>
        @endif

        @if (!empty($experience->technologies))
            <div class="mt-4 flex flex-wrap gap-1.5">
                @foreach ($experience->technologies as $tech)
                    <x-badge>{{ $tech }}</x-badge>
                @endforeach
            </div>
        @endif
    </div>
</li>
