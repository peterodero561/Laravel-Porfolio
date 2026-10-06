@props(['variant' => 'default'])

@php
    $variants = [
        'default' => 'border-line bg-white/5 text-fg-muted',
        'brand'   => 'border-brand-500/30 bg-brand-500/10 text-brand-300',
        'cyan'    => 'border-cyan-500/30 bg-cyan-500/10 text-cyan-400',
        'emerald' => 'border-emerald-500/30 bg-emerald-500/10 text-emerald-400',
    ];
    $classes = 'inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-medium ' . $variants[$variant];
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</span>