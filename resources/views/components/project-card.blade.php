@props(['project'])

@php
    $thumb = $project->thumbnail
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($project->thumbnail)
        : null;
@endphp

<a href="{{ route('projects.show', $project) }}"
   class="group block focus:outline-none"
   wire:navigate>
    <article class="flex h-full flex-col overflow-hidden rounded-xl border border-line bg-white/[0.02] transition hover:border-line-strong hover:bg-white/[0.04]">
        <div class="relative aspect-[16/10] overflow-hidden border-b border-line bg-ink-800">
            @if ($thumb)
                @php
                    $thumbMd = preg_replace('/\.[^.]+$/', '-md.webp', $project->thumbnail);
                    $thumbSm = preg_replace('/\.[^.]+$/', '-thumb.webp', $project->thumbnail);
                    $hasMd = \Illuminate\Support\Facades\Storage::disk('public')->exists($thumbMd);
                    $hasSm = \Illuminate\Support\Facades\Storage::disk('public')->exists($thumbSm);
                    $disk = \Illuminate\Support\Facades\Storage::disk('public');
                @endphp

                <img src="{{ $hasMd ? $disk->url($thumbMd) : $thumb }}"
                 @if ($hasMd && $hasSm)
                     srcset="{{ $disk->url($thumbSm) }} 480w, {{ $disk->url($thumbMd) }} 1200w"
                     sizes="(min-width: 1024px) 33vw, (min-width: 768px) 50vw, 100vw"
                 @endif
                 alt="{{ $project->title }}"
                 loading="lazy"
                 decoding="async"
                 width="640" height="400"
                 class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]">
            @else
                <div class="flex h-full items-center justify-center bg-gradient-to-br from-ink-800 to-ink-900">
                    <span class="font-mono text-xs text-fg-dim">{{ $project->category->label() }}</span>
                </div>
            @endif
        </div>

        <div class="flex flex-1 flex-col p-6">
            <div class="flex items-center gap-2">
                <x-badge variant="brand">{{ $project->category->label() }}</x-badge>
                @if ($project->project_date)
                    <span class="text-xs text-fg-dim">{{ $project->project_date->format('M Y') }}</span>
                @endif
            </div>

            <h3 class="mt-3 text-lg font-semibold tracking-tight group-hover:text-brand-300 transition">
                {{ $project->title }}
            </h3>

            <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-fg-muted">
                {{ $project->short_description }}
            </p>

            @if ($project->technologies->isNotEmpty())
                <div class="mt-5 flex flex-wrap gap-1.5">
                    @foreach ($project->technologies->take(4) as $tech)
                        <x-badge>{{ $tech->name }}</x-badge>
                    @endforeach
                    @if ($project->technologies->count() > 4)
                        <x-badge>+{{ $project->technologies->count() - 4 }}</x-badge>
                    @endif
                </div>
            @endif

            <div class="mt-auto pt-6 text-sm font-medium text-brand-400 group-hover:text-brand-300">
                Read case study →
            </div>
        </div>
    </article>
</a>