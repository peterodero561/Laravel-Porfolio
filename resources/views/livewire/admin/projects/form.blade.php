<div class="space-y-8" x-data="{ tab: 'basics' }">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">
                {{ $isEditing ? 'Edit project' : 'New project' }}
            </h1>
            <p class="mt-1 text-sm text-fg-muted">
                Fill in as much or as little as you need — empty sections are hidden on the public site.
            </p>
        </div>
        <div class="flex gap-2">
            <x-button :href="route('admin.projects')" variant="secondary">Cancel</x-button>
            <x-button type="button" wire:click="save"
                      wire:loading.attr="disabled" wire:target="save, thumbnail, newImages">
                <span wire:loading.remove wire:target="save">Save project</span>
                <span wire:loading wire:target="save">Saving…</span>
            </x-button>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex flex-wrap gap-1 border-b border-line">
        @foreach (['basics' => 'Basics', 'case' => 'Case study', 'media' => 'Media', 'publish' => 'Publish'] as $key => $label)
            <button type="button" @click="tab = '{{ $key }}'"
                    :class="tab === '{{ $key }}'
                        ? 'border-brand-500 text-fg'
                        : 'border-transparent text-fg-muted hover:text-fg'"
                    class="-mb-px border-b-2 px-4 py-2.5 text-sm font-medium transition">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- BASICS --}}
    <div x-show="tab === 'basics'" class="grid gap-6 lg:grid-cols-2">
        <div class="space-y-5">
            <div>
                <label for="title" class="block text-sm font-medium">Title</label>
                <input id="title" type="text" wire:model.live.debounce.300ms="title"
                       class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
                @error('title') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="slug" class="block text-sm font-medium">Slug</label>
                <input id="slug" type="text" wire:model="slug"
                       class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 font-mono text-sm text-fg focus:border-brand-500 focus:outline-none">
                @error('slug') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="short" class="block text-sm font-medium">Short description <span class="text-fg-dim">(≤500)</span></label>
                <textarea id="short" rows="3" wire:model="short_description"
                          class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none"></textarea>
                @error('short_description') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="category" class="block text-sm font-medium">Category</label>
                <select id="category" wire:model="category"
                        class="mt-2 w-full rounded-lg border border-line bg-ink-800 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->value }}">{{ $cat->label() }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="tech" class="block text-sm font-medium">Technologies <span class="text-fg-dim">(comma separated)</span></label>
                <input id="tech" type="text" wire:model="technologies_text"
                       placeholder="Laravel, Flutter, PostgreSQL"
                       class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg placeholder:text-fg-dim focus:border-brand-500 focus:outline-none">
                <p class="mt-1.5 text-xs text-fg-dim">New names are created automatically.</p>
            </div>
        </div>

        <div class="space-y-5">
            <div>
                <label for="gh" class="block text-sm font-medium">GitHub URL</label>
                <input id="gh" type="url" wire:model="github_url" placeholder="https://github.com/…"
                       class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
                @error('github_url') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="live" class="block text-sm font-medium">Live URL</label>
                <input id="live" type="url" wire:model="live_url" placeholder="https://…"
                       class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
                @error('live_url') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="date" class="block text-sm font-medium">Project date</label>
                <input id="date" type="date" wire:model="project_date"
                       class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
            </div>

            <div>
                <label for="sort" class="block text-sm font-medium">Sort order</label>
                <input id="sort" type="number" min="0" wire:model="sort_order"
                       class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
            </div>
        </div>
    </div>

    {{-- CASE STUDY --}}
    <div x-show="tab === 'case'" x-cloak class="space-y-5">
        <div>
            <label for="full" class="block text-sm font-medium">Full description</label>
            <textarea id="full" rows="4" wire:model="full_description"
                      class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none"></textarea>
        </div>

        <div class="grid gap-5 lg:grid-cols-2">
            <div>
                <label for="problem" class="block text-sm font-medium">Problem</label>
                <textarea id="problem" rows="4" wire:model="problem"
                          class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none"></textarea>
            </div>
            <div>
                <label for="solution" class="block text-sm font-medium">Solution</label>
                <textarea id="solution" rows="4" wire:model="solution"
                          class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none"></textarea>
            </div>
        </div>

        <div>
            <label for="features" class="block text-sm font-medium">Features <span class="text-fg-dim">(one per line)</span></label>
            <textarea id="features" rows="5" wire:model="features_text"
                      placeholder="Multi-tenant lease tracking&#10;AI-powered document extraction"
                      class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 font-mono text-sm text-fg focus:border-brand-500 focus:outline-none"></textarea>
        </div>

        <div>
            <label for="arch" class="block text-sm font-medium">Architecture <span class="text-fg-dim">(plain text or ASCII diagram)</span></label>
            <textarea id="arch" rows="6" wire:model="architecture"
                      class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 font-mono text-xs text-fg focus:border-brand-500 focus:outline-none"></textarea>
        </div>

        <div>
            <label for="ti" class="block text-sm font-medium">Technical implementation</label>
            <textarea id="ti" rows="4" wire:model="technical_implementation"
                      class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none"></textarea>
        </div>

        <div class="grid gap-5 lg:grid-cols-2">
            <div>
                <label for="challenges" class="block text-sm font-medium">Challenges</label>
                <textarea id="challenges" rows="3" wire:model="challenges"
                          class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none"></textarea>
            </div>
            <div>
                <label for="results" class="block text-sm font-medium">Results</label>
                <textarea id="results" rows="3" wire:model="results"
                          class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none"></textarea>
            </div>
        </div>
    </div>

    {{-- MEDIA --}}
    <div x-show="tab === 'media'" x-cloak class="space-y-6">
        <div>
            <label for="thumb" class="block text-sm font-medium">Thumbnail</label>
            <input id="thumb" type="file" wire:model="thumbnail" accept="image/jpeg,image/png,image/webp"
                   class="mt-2 block w-full text-sm text-fg-muted file:mr-3 file:rounded-md file:border-0 file:bg-brand-500 file:px-3 file:py-2 file:text-xs file:text-white hover:file:bg-brand-400">
            @error('thumbnail') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror

            <div wire:loading wire:target="thumbnail" class="mt-2 text-xs text-fg-dim">Uploading…</div>

            <div class="mt-4 flex items-start gap-4">
                @if ($thumbnail  && !is_string($thumbnail) && method_exists($thumbnail, 'isPreviewable') && $thumbnail->isPreviewable())
                    <div class="relative">
                        <img src="{{ $thumbnail->temporaryUrl() }}" alt="New thumbnail preview"
                             class="h-32 w-48 rounded-lg border border-line object-cover">
                        <button type="button" wire:click="removeThumbnail"
                                class="absolute -top-2 -right-2 rounded-full bg-red-500 px-2 py-0.5 text-xs text-white">×</button>
                    </div>
                @elseif ($existingThumbnail)
                    <div class="relative">
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($existingThumbnail) }}"
                             alt="Current thumbnail"
                             class="h-32 w-48 rounded-lg border border-line object-cover">
                        <button type="button" wire:click="removeThumbnail"
                                class="absolute -top-2 -right-2 rounded-full bg-red-500 px-2 py-0.5 text-xs text-white">×</button>
                    </div>
                @endif
            </div>
        </div>

        <div>
            <label for="gallery" class="block text-sm font-medium">Gallery images</label>
            <input id="gallery" type="file" wire:model="newImages" multiple accept="image/jpeg,image/png,image/webp"
                   class="mt-2 block w-full text-sm text-fg-muted file:mr-3 file:rounded-md file:border-0 file:bg-brand-500 file:px-3 file:py-2 file:text-xs file:text-white hover:file:bg-brand-400">
            @error('newImages.*') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror

            <div wire:loading wire:target="newImages" class="mt-2 text-xs text-fg-dim">Uploading…</div>

            @if ($newImages)
                <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
                    @foreach ($newImages as $img)
                        <img src="{{ $img->temporaryUrl() }}" alt="" class="aspect-video rounded-lg border border-line object-cover">
                    @endforeach
                </div>
            @endif
        </div>

        @if (!empty($existingImages))
            <div>
                <p class="text-sm font-medium">Current gallery</p>
                <div class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-4">
                    @foreach ($existingImages as $id => $path)
                        <div class="relative" wire:key="img-{{ $id }}">
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($path) }}"
                                 alt="" class="aspect-video rounded-lg border border-line object-cover">
                            <button type="button" wire:click="removeExistingImage({{ $id }})"
                                    wire:confirm="Remove this image?"
                                    class="absolute -top-2 -right-2 rounded-full bg-red-500 px-2 py-0.5 text-xs text-white">×</button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- PUBLISH --}}
    <div x-show="tab === 'publish'" x-cloak class="space-y-5">
        <label class="flex items-center gap-3">
            <input type="checkbox" wire:model="published" class="rounded border-line bg-white/5">
            <span class="text-sm">Published — visible on the public site</span>
        </label>

        <label class="flex items-center gap-3">
            <input type="checkbox" wire:model="featured" class="rounded border-line bg-white/5">
            <span class="text-sm">Featured — shows on the homepage</span>
        </label>
    </div>
</div>