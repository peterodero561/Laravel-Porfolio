<div class="space-y-8">
    <header>
        <h1 class="text-2xl font-semibold tracking-tight">Dashboard</h1>
        <p class="mt-1 text-sm text-fg-muted">Overview of your portfolio content.</p>
    </header>

    @php
        $cards = [
            ['label' => 'Total projects', 'value' => $stats['projects_total'], 'route' => 'admin.projects'],
            ['label' => 'Published', 'value' => $stats['projects_published'], 'route' => 'admin.projects'],
            ['label' => 'Featured', 'value' => $stats['projects_featured'], 'route' => 'admin.projects'],
            ['label' => 'Unread messages', 'value' => $stats['messages_unread'], 'route' => 'admin.messages', 'accent' => true],
            ['label' => 'Skills', 'value' => $stats['skills_total'], 'route' => 'admin.skills'],
            ['label' => 'Experience', 'value' => $stats['experience_total'], 'route' => 'admin.experience'],
            ['label' => 'Services', 'value' => $stats['services_total'], 'route' => 'admin.services'],
            ['label' => 'Messages total', 'value' => $stats['messages_total'], 'route' => 'admin.messages'],
        ];
    @endphp

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($cards as $card)
            <a href="{{ route($card['route']) }}"
               class="group rounded-xl border border-line bg-white/[0.02] p-5 transition hover:border-line-strong">
                <p class="text-xs font-mono uppercase tracking-[0.2em] text-fg-dim">{{ $card['label'] }}</p>
                <p @class([
                    'mt-3 text-3xl font-semibold tracking-tight',
                    'text-emerald-400' => ($card['accent'] ?? false) && $card['value'] > 0,
                ])>{{ $card['value'] }}</p>
            </a>
        @endforeach
    </div>

    <div class="flex flex-wrap gap-3">
        <x-button :href="route('admin.projects.create')">New project</x-button>
        <x-button :href="route('admin.settings')" variant="secondary">Site settings</x-button>
        <x-button :href="route('admin.messages')" variant="secondary">View messages</x-button>
    </div>
</div>