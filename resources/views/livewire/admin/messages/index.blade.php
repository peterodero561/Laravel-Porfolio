<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold tracking-tight">Messages</h1>
        <p class="mt-1 text-sm text-fg-muted">Contact form submissions.</p>
    </div>

    <div class="flex flex-wrap gap-1.5">
        @foreach (['all' => 'All', 'unread' => 'Unread', 'read' => 'Read'] as $value => $label)
            <button wire:click="$set('filter', '{{ $value }}')"
                    @class([
                        'rounded-full border px-3.5 py-1.5 text-xs',
                        'border-brand-500/40 bg-brand-500/10 text-brand-300' => $filter === $value,
                        'border-line bg-white/5 text-fg-muted hover:text-fg' => $filter !== $value,
                    ])>{{ $label }}</button>
        @endforeach
    </div>

    <div class="overflow-hidden rounded-xl border border-line" wire:loading.class.delay="opacity-60" wire:target="filter, open, delete">
        <table class="w-full text-sm">
            <thead class="border-b border-line bg-white/[0.02] text-left text-xs uppercase tracking-wider text-fg-dim">
                <tr>
                    <th class="px-4 py-3 font-medium w-2"></th>
                    <th class="px-4 py-3 font-medium">From</th>
                    <th class="hidden px-4 py-3 font-medium sm:table-cell">Subject</th>
                    <th class="px-4 py-3 font-medium">Received</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @forelse ($messages as $message)
                    <tr wire:key="msg-{{ $message->id }}" class="cursor-pointer hover:bg-white/[0.02]"
                        wire:click="open({{ $message->id }})">
                        <td class="px-4 py-3">
                            @if ($message->read_at === null)
                                <span class="inline-block h-2 w-2 rounded-full bg-emerald-400" aria-label="Unread"></span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium">{{ $message->name }}</div>
                            <div class="text-xs text-fg-dim">{{ $message->email }}</div>
                        </td>
                        <td class="hidden px-4 py-3 text-fg-muted sm:table-cell">{{ $message->subject }}</td>
                        <td class="px-4 py-3 text-fg-muted">{{ $message->created_at->diffForHumans() }}</td>
                        <td class="px-4 py-3 text-right" wire:click.stop>
                            <button wire:click="delete({{ $message->id }})" wire:confirm="Delete this message?"
                                    class="text-xs text-red-400 hover:text-red-300">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-12 text-center text-fg-muted">No messages.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $messages->links() }}</div>

    @if ($viewing)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-ink-950/70 backdrop-blur-sm p-4"
             wire:click.self="close">
            <div class="w-full max-w-2xl rounded-2xl border border-line bg-ink-900 p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold">{{ $viewing->subject }}</h2>
                        <p class="mt-1 text-sm text-fg-muted">
                            {{ $viewing->name }} · <a href="mailto:{{ $viewing->email }}" class="text-brand-400 hover:text-brand-300">{{ $viewing->email }}</a>
                        </p>
                        <p class="mt-1 text-xs text-fg-dim">
                            {{ $viewing->created_at->format('M j, Y · H:i') }}
                            @if ($viewing->ip_address) · {{ $viewing->ip_address }} @endif
                        </p>
                    </div>
                    <button wire:click="close" class="rounded-md p-1 text-fg-dim hover:text-fg" aria-label="Close">✕</button>
                </div>

                <div class="mt-6 max-h-[50vh] overflow-y-auto rounded-lg border border-line bg-white/[0.02] p-4 text-sm leading-relaxed text-fg-muted whitespace-pre-wrap">{{ $viewing->message }}</div>

                <div class="mt-6 flex flex-wrap items-center justify-end gap-3">
                    <x-button type="button" variant="secondary" wire:click="markUnread({{ $viewing->id }})">Mark unread</x-button>
                    <x-button type="button" variant="secondary" :href="'mailto:' . $viewing->email">Reply by email</x-button>
                    <x-button type="button"
                              wire:click="delete({{ $viewing->id }})"
                              wire:confirm="Delete this message?">Delete</x-button>
                </div>
            </div>
        </div>
    @endif
</div>