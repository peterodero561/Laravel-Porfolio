<div>
    <section class="border-b border-line">
        <div class="container-page py-16 sm:py-20">
            <p class="font-mono text-xs uppercase tracking-[0.25em] text-brand-400">Contact</p>
            <h1 class="mt-4 text-4xl font-semibold tracking-tight sm:text-5xl">Let's build something.</h1>
            <p class="mt-4 max-w-2xl text-fg-muted">
                Tell me about your project. I usually reply within one working day.
            </p>
        </div>
    </section>

    <section class="container-page grid gap-12 py-16 lg:grid-cols-[1fr_320px] lg:gap-20">
        <form wire:submit="submit" class="space-y-6" novalidate>
            @if ($sent)
                <div class="rounded-lg border border-emerald-500/30 bg-emerald-500/10 p-4 text-sm text-emerald-300"
                     role="status" aria-live="polite">
                    Thanks — your message has been sent. I'll get back to you shortly.
                </div>
            @endif

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label for="name" class="block text-sm font-medium">Name</label>
                    <input id="name" type="text" wire:model="name"
                           @error('name') aria-invalid="true" aria-describedby="name-error" @enderror
                           class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg placeholder:text-fg-dim focus:border-brand-500 focus:outline-none">
                    @error('name') <p id="name-error" class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input id="email" type="email" wire:model="email"
                           @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
                           class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg placeholder:text-fg-dim focus:border-brand-500 focus:outline-none">
                    @error('email') <p id="email-error" class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="subject" class="block text-sm font-medium">Subject</label>
                <input id="subject" type="text" wire:model="subject"
                       @error('subject') aria-invalid="true" aria-describedby="subject-error" @enderror
                       class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg placeholder:text-fg-dim focus:border-brand-500 focus:outline-none">
                @error('subject') <p id="subject-error" class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="message" class="block text-sm font-medium">Message</label>
                <textarea id="message" rows="6" wire:model="message"
                          @error('message') aria-invalid="true" aria-describedby="message-error" @enderror
                          class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg placeholder:text-fg-dim focus:border-brand-500 focus:outline-none"></textarea>
                @error('message') <p id="message-error" class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-4">
                <x-button type="submit" size="lg" wire:loading.attr="disabled" wire:target="submit">
                    <span wire:loading.remove wire:target="submit">Send message</span>
                    <span wire:loading wire:target="submit">Sending…</span>
                </x-button>
            </div>
        </form>

        <aside class="space-y-8">
            <div>
                <p class="text-xs font-mono uppercase tracking-[0.2em] text-fg-dim">Email</p>
                <p class="mt-2 text-sm">
                    <a href="mailto:{{ $portfolio->setting('email') }}" class="hover:text-brand-300">
                        {{ $portfolio->setting('email', '—') }}
                    </a>
                </p>
            </div>

            @if ($portfolio->setting('location'))
                <div>
                    <p class="text-xs font-mono uppercase tracking-[0.2em] text-fg-dim">Location</p>
                    <p class="mt-2 text-sm">{{ $portfolio->setting('location') }}</p>
                </div>
            @endif

            <div>
                <p class="text-xs font-mono uppercase tracking-[0.2em] text-fg-dim">Availability</p>
                <p class="mt-2 flex items-center gap-2 text-sm">
                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                    Open to new projects
                </p>
            </div>
        </aside>
    </section>
</div>