@props(['code' => '500', 'title' => 'Something went wrong', 'message' => null])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>{{ $code }} — {{ $title }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|jetbrains-mono:400,500" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-dvh bg-ink-950 text-fg antialiased">
    <main class="container-page flex min-h-dvh items-center justify-center py-20">
        <div class="max-w-lg text-center">
            <p class="font-mono text-sm text-brand-400">Error {{ $code }}</p>
            <h1 class="mt-3 text-4xl font-semibold tracking-tight sm:text-5xl">{{ $title }}</h1>
            @if ($message)
                <p class="mt-4 text-fg-muted">{{ $message }}</p>
            @endif
            <a href="{{ url('/') }}"
               class="mt-8 inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-brand-400">
                ← Back to homepage
            </a>
        </div>
    </main>
</body>
</html>