@props(['title' => null])

<header class="flex h-16 items-center justify-between border-b border-line bg-ink-950/70 px-5 backdrop-blur-md sm:px-8">
    <h1 class="text-sm font-medium text-fg-muted">{{ $title ?? 'Dashboard' }}</h1>

    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit"
                class="rounded-md border border-line px-3 py-1.5 text-xs text-fg-muted transition hover:border-line-strong hover:text-fg">
            Sign out
        </button>
    </form>
</header>