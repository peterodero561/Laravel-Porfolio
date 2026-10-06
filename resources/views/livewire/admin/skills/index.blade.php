<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Skills</h1>
            <p class="mt-1 text-sm text-fg-muted">Grouped by category on the public site.</p>
        </div>
        <x-button wire:click="create" type="button">New skill</x-button>
    </div>

    <div class="relative w-full sm:w-64">
        <label for="q" class="sr-only">Search</label>
        <input id="q" type="search" wire:model.live.debounce.300ms="search" placeholder="Search skills…"
               class="w-full rounded-lg border border-line bg-white/5 px-3.5 py-2 text-sm text-fg placeholder:text-fg-dim focus:border-brand-500 focus:outline-none">
    </div>

    <div class="overflow-hidden rounded-xl border border-line" wire:loading.class.delay="opacity-60" wire:target="search, delete">
        <table class="w-full text-sm">
            <thead class="border-b border-line bg-white/[0.02] text-left text-xs uppercase tracking-wider text-fg-dim">
                <tr>
                    <th class="px-4 py-3 font-medium">Name</th>
                    <th class="px-4 py-3 font-medium">Category</th>
                    <th class="px-4 py-3 font-medium">State</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @forelse ($skills as $skill)
                    <tr wire:key="skill-{{ $skill->id }}">
                        <td class="px-4 py-3 font-medium">{{ $skill->name }}</td>
                        <td class="px-4 py-3"><x-badge variant="brand">{{ $skill->category->label() }}</x-badge></td>
                        <td class="px-4 py-3">
                            <x-badge :variant="$skill->published ? 'emerald' : 'default'">
                                {{ $skill->published ? 'Published' : 'Hidden' }}
                            </x-badge>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button wire:click="edit({{ $skill->id }})" class="text-xs text-brand-400 hover:text-brand-300">Edit</button>
                            <button wire:click="delete({{ $skill->id }})" wire:confirm="Delete this skill?"
                                    class="ml-3 text-xs text-red-400 hover:text-red-300">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-12 text-center text-fg-muted">No skills yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $skills->links() }}</div>

    @if ($showModal)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-ink-950/70 backdrop-blur-sm p-4"
             wire:click.self="$set('showModal', false)">
            <div class="w-full max-w-md rounded-2xl border border-line bg-ink-900 p-6">
                <h2 class="text-lg font-semibold">{{ $editingId ? 'Edit skill' : 'New skill' }}</h2>

                <form wire:submit="save" class="mt-6 space-y-4">
                    <div>
                        <label for="sname" class="block text-sm font-medium">Name</label>
                        <input id="sname" type="text" wire:model="name"
                               class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
                        @error('name') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="scat" class="block text-sm font-medium">Category</label>
                        <select id="scat" wire:model="category"
                                class="mt-2 w-full rounded-lg border border-line bg-ink-800 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
                            @foreach ($categories as $c)
                                <option value="{{ $c->value }}">{{ $c->label() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="ssort" class="block text-sm font-medium">Sort order</label>
                            <input id="ssort" type="number" min="0" wire:model="sort_order"
                                   class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
                        </div>
                        <label class="mt-7 flex items-center gap-2 text-sm">
                            <input type="checkbox" wire:model="published" class="rounded border-line bg-white/5">
                            Published
                        </label>
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