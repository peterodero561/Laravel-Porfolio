@props(['as' => 'div'])

@php
    $classes = 'rounded-xl border border-line bg-white/[0.02] p-6 transition';
@endphp

<{{ $as }} {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</{{ $as }}>