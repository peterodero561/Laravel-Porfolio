<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Services</h1>
            <p class="mt-1 text-sm text-fg-muted">Shown on the homepage and services page.</p>
        </div>
        <x-button wire:click="create" type="button">New service</x-button>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3" wire:loading.class.delay="opacity-60" wire:target="delete, togglePublished">
        @forelse ($services as $service)
            <div wire:key="service-{{ $service->id }}" class="rounded-xl border border-line bg-white/[0.02] p-5">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="font-semibold">{{ $service->title }}</h3>
                        <p class="mt-1 text-xs text-fg-dim">Sort: {{ $service->sort_order }}</p>
                    </div>
                    <button wire:click="togglePublished({{ $service->id }})"
                            @class([
                                'rounded-full border px-2 py-0.5 text-[11px]',
                                'border-emerald-500/40 bg-emerald-500/10 text-emerald-400' => $service->published,
                                'border-line bg-white/5 text-fg-dim' => ! $service->published,
                            ])>
                        {{ $service->published ? 'Published' : 'Hidden' }}
                    </button>
                </div>

                <p class="mt-3 text-sm text-fg-muted line-clamp-3">{{ $service->description }}</p>

                @if (!empty($service->technologies))
                    <div class="mt-4 flex flex-wrap gap-1.5">
                        @foreach ($service->technologies as $tech)
                            <x-badge>{{ $tech }}</x-badge>
                        @endforeach
                    </div>
                @endif

                <div class="mt-5 flex gap-3 text-xs">
                    <button wire:click="edit({{ $service->id }})" class="text-brand-400 hover:text-brand-300">Edit</button>
                    <button wire:click="delete({{ $service->id }})" wire:confirm="Delete this service?"
                            class="text-red-400 hover:text-red-300">Delete</button>
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-xl border border-line bg-white/[0.02] p-12 text-center text-fg-muted">
                No services yet.
            </div>
        @endforelse
    </div>

    @if ($showModal)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-ink-950/70 backdrop-blur-sm p-4"
             wire:click.self="$set('showModal', false)">
            <div class="w-full max-w-lg rounded-2xl border border-line bg-ink-900 p-6">
                <h2 class="text-lg font-semibold">{{ $editingId ? 'Edit service' : 'New service' }}</h2>

                <form wire:submit="save" class="mt-6 space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-medium">Title</label>
                        <input id="title" type="text" wire:model="title"
                               class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
                        @error('title') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="desc" class="block text-sm font-medium">Description</label>
                        <textarea id="desc" rows="4" wire:model="description"
                                  class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none"></textarea>
                        @error('description') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="icon" class="block text-sm font-medium">Icon <span class="text-fg-dim">(glyph/emoji)</span></label>
                            <input id="icon" type="text" wire:model="icon" maxlength="4"
                                   class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
                        </div>
                        <div>
                            <label for="sort" class="block text-sm font-medium">Sort order</label>
                            <input id="sort" type="number" min="0" wire:model="sort_order"
                                   class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label for="tech" class="block text-sm font-medium">Technologies <span class="text-fg-dim">(comma separated)</span></label>
                        <input id="tech" type="text" wire:model="technologies_text"
                               class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
                    </div>

                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" wire:model="published" class="rounded border-line bg-white/5">
                        Published
                    </label>

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