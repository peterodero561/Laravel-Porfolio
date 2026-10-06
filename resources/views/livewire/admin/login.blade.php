<div class="rounded-2xl border border-line bg-ink-900/60 p-8 backdrop-blur">
    <div class="flex items-center gap-2">
        <span class="inline-block h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
        <span class="text-sm font-semibold tracking-tight">Admin</span>
    </div>

    <h1 class="mt-6 text-2xl font-semibold tracking-tight">Sign in</h1>
    <p class="mt-1 text-sm text-fg-muted">Restricted area. Public site remains open.</p>

    <form wire:submit="login" class="mt-8 space-y-5" novalidate>
        <div>
            <label for="email" class="block text-sm font-medium">Email</label>
            <input id="email" type="email" wire:model="email" autocomplete="username"
                   @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
                   class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
            @error('email') <p id="email-error" class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium">Password</label>
            <input id="password" type="password" wire:model="password" autocomplete="current-password"
                   class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
        </div>

        <label class="flex items-center gap-2 text-sm text-fg-muted">
            <input type="checkbox" wire:model="remember" class="rounded border-line bg-white/5">
            Remember me
        </label>

        <x-button type="submit" size="lg" class="w-full justify-center"
                  wire:loading.attr="disabled" wire:target="login">
            <span wire:loading.remove wire:target="login">Sign in</span>
            <span wire:loading wire:target="login">Signing in…</span>
        </x-button>
    </form>
</div>