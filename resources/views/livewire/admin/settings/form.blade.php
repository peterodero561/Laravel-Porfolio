<div class="space-y-8 max-w-3xl">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Site settings</h1>
            <p class="mt-1 text-sm text-fg-muted">These appear across the public site.</p>
        </div>
        <x-button type="button" wire:click="save" wire:loading.attr="disabled" wire:target="save, profile_image, resume_file">
            <span wire:loading.remove wire:target="save">Save changes</span>
            <span wire:loading wire:target="save">Saving…</span>
        </x-button>
    </div>

    {{-- IDENTITY --}}
    <fieldset class="rounded-xl border border-line bg-white/[0.02] p-6 space-y-5">
        <legend class="px-2 text-xs font-mono uppercase tracking-[0.2em] text-fg-dim">Identity</legend>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-medium">Name</label>
                <input id="name" type="text" wire:model="name"
                       class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
                @error('name') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="ptitle" class="block text-sm font-medium">Professional title</label>
                <input id="ptitle" type="text" wire:model="professional_title"
                       class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
                @error('professional_title') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="bio" class="block text-sm font-medium">Short bio</label>
            <textarea id="bio" rows="3" wire:model="short_bio"
                      class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none"></textarea>
            @error('short_bio') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="avail" class="block text-sm font-medium">Availability</label>
                <select id="avail" wire:model="availability_status"
                        class="mt-2 w-full rounded-lg border border-line bg-ink-800 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
                    <option value="available">Available for work</option>
                    <option value="limited">Limited availability</option>
                    <option value="unavailable">Not available</option>
                </select>
            </div>
            <div>
                <label for="loc" class="block text-sm font-medium">Location</label>
                <input id="loc" type="text" wire:model="location"
                       class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
            </div>
        </div>
    </fieldset>

    {{-- CONTACT --}}
    <fieldset class="rounded-xl border border-line bg-white/[0.02] p-6 space-y-5">
        <legend class="px-2 text-xs font-mono uppercase tracking-[0.2em] text-fg-dim">Contact</legend>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="email" class="block text-sm font-medium">Email</label>
                <input id="email" type="email" wire:model="email"
                       class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
                @error('email') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium">Phone</label>
                <input id="phone" type="text" wire:model="phone"
                       class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
            </div>
        </div>
    </fieldset>

    {{-- SOCIAL --}}
    <fieldset class="rounded-xl border border-line bg-white/[0.02] p-6 space-y-5">
        <legend class="px-2 text-xs font-mono uppercase tracking-[0.2em] text-fg-dim">Social</legend>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="gh" class="block text-sm font-medium">GitHub</label>
                <input id="gh" type="url" wire:model="github_url"
                       class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
                @error('github_url') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="li" class="block text-sm font-medium">LinkedIn</label>
                <input id="li" type="url" wire:model="linkedin_url"
                       class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
                @error('linkedin_url') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="tw" class="block text-sm font-medium">Twitter / X</label>
                <input id="tw" type="url" wire:model="twitter_url"
                       class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
            </div>
            <div>
                <label for="ws" class="block text-sm font-medium">Website</label>
                <input id="ws" type="url" wire:model="website_url"
                       class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
            </div>
        </div>
    </fieldset>

    {{-- MEDIA --}}
    <fieldset class="rounded-xl border border-line bg-white/[0.02] p-6 space-y-5">
        <legend class="px-2 text-xs font-mono uppercase tracking-[0.2em] text-fg-dim">Media</legend>

        <div>
            <label for="pi" class="block text-sm font-medium">Profile image</label>
            <input id="pi" type="file" wire:model="profile_image" accept="image/jpeg,image/png,image/webp"
                   class="mt-2 block w-full text-sm text-fg-muted file:mr-3 file:rounded-md file:border-0 file:bg-brand-500 file:px-3 file:py-2 file:text-xs file:text-white hover:file:bg-brand-400">
            @error('profile_image') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror

            <div wire:loading wire:target="profile_image" class="mt-2 text-xs text-fg-dim">Uploading…</div>

            @if ($profile_image || $existingProfileImage)
                <div class="mt-4 flex items-center gap-4">
                    <img src="{{ $profile_image ? $profile_image->temporaryUrl() : \Illuminate\Support\Facades\Storage::disk('public')->url($existingProfileImage) }}"
                         alt="" class="h-20 w-20 rounded-xl border border-line object-cover">
                    <button type="button" wire:click="removeProfileImage"
                            class="text-xs text-red-400 hover:text-red-300">Remove</button>
                </div>
            @endif
        </div>

        <div>
            <label for="rf" class="block text-sm font-medium">Resume (PDF)</label>
            <input id="rf" type="file" wire:model="resume_file" accept="application/pdf"
                   class="mt-2 block w-full text-sm text-fg-muted file:mr-3 file:rounded-md file:border-0 file:bg-brand-500 file:px-3 file:py-2 file:text-xs file:text-white hover:file:bg-brand-400">
            @error('resume_file') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror

            <div wire:loading wire:target="resume_file" class="mt-2 text-xs text-fg-dim">Uploading…</div>

            @if ($resume_file || $existingResumeFile)
                <div class="mt-4 flex items-center gap-4">
                    <span class="font-mono text-xs text-fg-muted">
                        {{ $resume_file ? $resume_file->getClientOriginalName() : 'resume.pdf' }}
                    </span>
                    <button type="button" wire:click="removeResume"
                            class="text-xs text-red-400 hover:text-red-300">Remove</button>
                </div>
            @endif
        </div>
    </fieldset>

    <div class="flex justify-end">
        <x-button type="button" wire:click="save" wire:loading.attr="disabled" wire:target="save, profile_image, resume_file">
            <span wire:loading.remove wire:target="save">Save changes</span>
            <span wire:loading wire:target="save">Saving…</span>
        </x-button>
    </div>
</div>