@props([
    'name' => 'Software Developer',
    'skills' => 'Laravel · Flutter · AI · PostgreSQL · IoT',
    'status' => 'Building useful software',
])

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-xl border border-line bg-ink-900/70 font-mono text-sm backdrop-blur']) }}>
    <div class="flex items-center gap-1.5 border-b border-line px-4 py-3">
        <span class="h-2.5 w-2.5 rounded-full bg-red-500/70"></span>
        <span class="h-2.5 w-2.5 rounded-full bg-yellow-500/70"></span>
        <span class="h-2.5 w-2.5 rounded-full bg-emerald-500/70"></span>
        <span class="ml-3 text-xs text-fg-dim">~/portfolio</span>
    </div>

    <div class="space-y-4 p-5">
        <div>
            <p><span class="text-brand-400">$</span> <span class="text-fg-muted">whoami</span></p>
            <p class="mt-1 text-fg">{{ $name }}</p>
        </div>

        <div>
            <p><span class="text-brand-400">$</span> <span class="text-fg-muted">skills</span></p>
            <p class="mt-1 text-fg">{{ $skills }}</p>
        </div>

        <div>
            <p><span class="text-brand-400">$</span> <span class="text-fg-muted">status</span></p>
            <p class="mt-1 text-emerald-400">
                {{ $status }}<span class="ml-0.5 inline-block h-4 w-1.5 translate-y-0.5 bg-emerald-400 animate-pulse"></span>
            </p>
        </div>
    </div>
</div>