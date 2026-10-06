@props([
    'href' => null,
    'variant' => 'primary',
    'size' => 'md',
])

@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-lg font-medium transition focus:outline-none disabled:opacity-50 disabled:pointer-events-none';

    $sizes = [
        'sm' => 'px-3.5 py-2 text-xs',
        'md' => 'px-5 py-2.5 text-sm',
        'lg' => 'px-6 py-3 text-sm',
    ];

    $variants = [
        'primary' => 'bg-brand-500 text-white hover:bg-brand-400',
        'secondary' => 'border border-line-strong bg-white/5 text-fg hover:bg-white/10',
        'ghost' => 'text-fg-muted hover:text-fg hover:bg-white/5',
        'success' => 'bg-emerald-500 text-white hover:bg-emerald-400',
    ];

    $classes = trim("{$base} {$sizes[$size]} {$variants[$variant]}");
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes, 'type' => 'button']) }}>
        {{ $slot }}
    </button>
@endif