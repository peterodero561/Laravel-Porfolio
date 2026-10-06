@props(['label', 'description'])

<div class="border-t border-line pt-6">
    <p class="font-mono text-xs uppercase tracking-[0.2em] text-brand-400">{{ $label }}</p>
    <p class="mt-3 text-sm leading-relaxed text-fg-muted">{{ $description }}</p>
</div>