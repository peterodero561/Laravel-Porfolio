@props(['service'])

<div class="flex h-full flex-col rounded-xl border border-line bg-white/[0.02] p-6 transition hover:border-line-strong">
    <div class="flex h-10 w-10 items-center justify-center rounded-lg border border-line bg-brand-500/10 text-brand-400">
        <span aria-hidden="true">{{ $service->icon ?? '◆' }}</span>
    </div>

    <h3 class="mt-5 text-base font-semibold tracking-tight">{{ $service->title }}</h3>
    <p class="mt-2 flex-1 text-sm leading-relaxed text-fg-muted">{{ $service->description }}</p>

    @if (!empty($service->technologies))
        <div class="mt-5 flex flex-wrap gap-1.5">
            @foreach ($service->technologies as $tech)
                <x-badge>{{ $tech }}</x-badge>
            @endforeach
        </div>
    @endif
</div>