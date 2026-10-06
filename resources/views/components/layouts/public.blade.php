@props(['seo' => null])

@php
    $seo ??= \App\Support\Seo::make();
    $resolved = $seo->resolve(app(\App\Services\PortfolioService::class)->settings());
@endphp

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <x-seo-meta :seo="$resolved" />

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|jetbrains-mono:400,500&display=swap" rel="stylesheet">

    {{ $head ?? '' }}

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-dvh bg-ink-950 text-fg antialiased">
    <a href="#main"
       class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50
              focus:rounded-md focus:bg-brand-500 focus:px-4 focus:py-2 focus:text-white">
        Skip to content
    </a>

    <x-public.nav />

    <main id="main">
        {{ $slot }}
    </main>

    <x-public.footer />

    @livewireScripts
</body>
</html>