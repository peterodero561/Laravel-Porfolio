@php
    $items = [
        ['label' => 'Dashboard',  'route' => 'admin.dashboard',  'icon' => '▦'],
        ['label' => 'Projects',   'route' => 'admin.projects',   'icon' => '◱'],
        ['label' => 'Skills',     'route' => 'admin.skills',     'icon' => '◆'],
        ['label' => 'Experience', 'route' => 'admin.experience', 'icon' => '▤'],
        ['label' => 'Services',   'route' => 'admin.services',   'icon' => '◇'],
        ['label' => 'Messages',   'route' => 'admin.messages',   'icon' => '✉'],
        ['label' => 'Account',    'route' => 'admin.account',    'icon' => '◎'],
        ['label' => 'Settings',   'route' => 'admin.settings',   'icon' => '⚙'],
    ];
@endphp

<div class="flex h-full flex-col">
    <div class="flex h-16 items-center border-b border-line px-6">
        <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold tracking-tight">
            <span class="text-brand-400">◆</span> Admin
        </a>
    </div>

    <nav class="flex-1 space-y-1 p-3" aria-label="Admin">
        @foreach ($items as $item)
            <a href="{{ route($item['route']) }}"
               @class([
                   'flex items-center gap-3 rounded-md px-3 py-2 text-sm transition',
                   'bg-white/5 text-fg' => request()->routeIs($item['route']),
                   'text-fg-muted hover:bg-white/5 hover:text-fg' => ! request()->routeIs($item['route']),
               ])>
                <span class="w-4 text-center text-fg-dim">{{ $item['icon'] }}</span>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>

    <div class="border-t border-line p-3">
        <a href="{{ route('home') }}"
           class="block rounded-md px-3 py-2 text-sm text-fg-muted hover:bg-white/5 hover:text-fg">
            ← View site
        </a>
    </div>
</div>