<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Experience</h1>
            <p class="mt-1 text-sm text-fg-muted">Appears as a timeline on the homepage and about page.</p>
        </div>
        <x-button wire:click="create" type="button">New entry</x-button>
    </div>

    <div class="overflow-hidden rounded-xl border border-line" wire:loading.class.delay="opacity-60" wire:target="delete">
        <table class="w-full text-sm">
            <thead class="border-b border-line bg-white/[0.02] text-left text-xs uppercase tracking-wider text-fg-dim">
                <tr>
                    <th class="px-4 py-3 font-medium">Position</th>
                    <th class="hidden px-4 py-3 font-medium sm:table-cell">Company</th>
                    <th class="px-4 py-3 font-medium">Period</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @forelse ($entries as $entry)
                    <tr wire:key="exp-{{ $entry->id }}">
                        <td class="px-4 py-3 font-medium">{{ $entry->position }}</td>
                        <td class="hidden px-4 py-3 text-fg-muted sm:table-cell">{{ $entry->company }}</td>
                        <td class="px-4 py-3 text-fg-muted">
                            {{ $entry->start_date->format('M Y') }} —
                            {{ $entry->is_current ? 'Present' : $entry->end_date?->format('M Y') }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button wire:click="edit({{ $entry->id }})" class="text-xs text-brand-400 hover:text-brand-300">Edit</button>
                            <button wire:click="delete({{ $entry->id }})" wire:confirm="Delete this entry?"
                                    class="ml-3 text-xs text-red-400 hover:text-red-300">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-12 text-center text-fg-muted">No experience entries yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($showModal)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-ink-950/70 backdrop-blur-sm p-4 overflow-y-auto"
             wire:click.self="$set('showModal', false)">
            <div class="w-full max-w-2xl rounded-2xl border border-line bg-ink-900 p-6 my-8">
                <h2 class="text-lg font-semibold">{{ $editingId ? 'Edit experience' : 'New experience' }}</h2>

                <form wire:submit="save" class="mt-6 space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="company" class="block text-sm font-medium">Company</label>
                            <input id="company" type="text" wire:model="company"
                                   class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
                            @error('company') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="position" class="block text-sm font-medium">Position</label>
                            <input id="position" type="text" wire:model="position"
                                   class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
                            @error('position') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="location" class="block text-sm font-medium">Location</label>
                            <input id="location" type="text" wire:model="location"
                                   class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
                        </div>
                        <div>
                            <label for="sort" class="block text-sm font-medium">Sort order</label>
                            <input id="sort" type="number" min="0" wire:model="sort_order"
                                   class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
                        </div>
                        <div>
                            <label for="start" class="block text-sm font-medium">Start date</label>
                            <input id="start" type="date" wire:model="start_date"
                                   class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
                            @error('start_date') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="end" class="block text-sm font-medium">End date</label>
                            <input id="end" type="date" wire:model="end_date" @disabled($is_current)
                                   class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none disabled:opacity-50">
                            @error('end_date') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" wire:model.live="is_current" class="rounded border-line bg-white/5">
                        Current position
                    </label>

                    <div>
                        <label for="desc" class="block text-sm font-medium">Description</label>
                        <textarea id="desc" rows="3" wire:model="description"
                                  class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none"></textarea>
                    </div>

                    <div>
                        <label for="resp" class="block text-sm font-medium">Responsibilities <span class="text-fg-dim">(one per line)</span></label>
                        <textarea id="resp" rows="4" wire:model="responsibilities_text"
                                  class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 font-mono text-xs text-fg focus:border-brand-500 focus:outline-none"></textarea>
                    </div>

                    <div>
                        <label for="tech" class="block text-sm font-medium">Technologies <span class="text-fg-dim">(comma separated)</span></label>
                        <input id="tech" type="text" wire:model="technologies_text"
                               class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <x-button type="button" variant="secondary" wire:click="$set('showModal', false)">Cancel</x-button>
                        <x-button type="submit" wire:loading.attr="disabled" wire:target="save">
                            <span wire:loading.remove wire:target="save">Save</span>
                            <span wire:loading wire:target="save">Saving…</span>
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>