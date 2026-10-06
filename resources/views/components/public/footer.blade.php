<footer class="mt-32 border-t border-line">
    <div class="container-page grid gap-10 py-14 sm:grid-cols-2 lg:grid-cols-4">
        <div class="sm:col-span-2">
            <p class="font-semibold">{{ config('app.name') }}</p>
            <p class="mt-2 max-w-sm text-sm text-fg-muted">
                Building web, mobile, AI and automation products with Laravel, Flutter and modern infrastructure.
            </p>
        </div>

        <div>
            <p class="text-xs font-medium uppercase tracking-wider text-fg-dim">Explore</p>
            <ul class="mt-3 space-y-2 text-sm">
                <li><a class="text-fg-muted hover:text-fg" href="{{ route('projects.index') }}">Projects</a></li>
                <li><a class="text-fg-muted hover:text-fg" href="{{ route('about') }}">About</a></li>
                <li><a class="text-fg-muted hover:text-fg" href="{{ route('services') }}">Services</a></li>
                <li><a class="text-fg-muted hover:text-fg" href="{{ route('resume') }}">Resume</a></li>
            </ul>
        </div>

        <div>
            <p class="text-xs font-medium uppercase tracking-wider text-fg-dim">Elsewhere</p>
            <ul class="mt-3 space-y-2 text-sm">
                <li><a class="text-fg-muted hover:text-fg" href="#" rel="noopener">GitHub</a></li>
                <li><a class="text-fg-muted hover:text-fg" href="#" rel="noopener">LinkedIn</a></li>
                <li><a class="text-fg-muted hover:text-fg" href="#" rel="noopener">X / Twitter</a></li>
            </ul>
        </div>
    </div>

    <div class="border-t border-line">
        <div class="container-page flex flex-col gap-2 py-6 text-xs text-fg-dim sm:flex-row sm:items-center sm:justify-between">
            <p>© {{ now()->year }} {{ config('app.name') }}. All rights reserved.</p>
            <p class="font-mono">Built with Laravel · Livewire · Tailwind</p>
        </div>
    </div>
</footer>