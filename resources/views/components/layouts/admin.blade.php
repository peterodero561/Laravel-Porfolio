@props(['title' => null])

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title ? "{$title} — Admin" : 'Admin' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|jetbrains-mono:400,500" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-dvh bg-ink-950 text-fg antialiased">
    <div class="flex min-h-dvh">
        <aside class="hidden w-64 shrink-0 border-r border-line bg-ink-900 lg:block">
            <x-admin.sidebar />
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <x-admin.topbar :title="$title" />
            <main class="flex-1 px-5 py-8 sm:px-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    <livewire:toasts />

    @livewireScripts
</body>
</html>