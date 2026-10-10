<div class="max-w-3xl space-y-8">
    <div>
        <h1 class="text-2xl font-semibold tracking-tight">Account</h1>
        <p class="mt-1 text-sm text-fg-muted">Update your admin login details.</p>
    </div>

    <fieldset class="space-y-5 rounded-xl border border-line bg-white/[0.02] p-6">
        <legend class="px-2 text-xs font-mono uppercase tracking-[0.2em] text-fg-dim">Login details</legend>

        <div>
            <label for="account-name" class="block text-sm font-medium">Display name</label>
            <input id="account-name" type="text" wire:model="name" autocomplete="name"
                   class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
            @error('name') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="account-email" class="block text-sm font-medium">Login email</label>
            <input id="account-email" type="email" wire:model="email" autocomplete="username"
                   class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
            @error('email') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
        </div>
    </fieldset>

    <fieldset class="space-y-5 rounded-xl border border-line bg-white/[0.02] p-6">
        <legend class="px-2 text-xs font-mono uppercase tracking-[0.2em] text-fg-dim">Change password</legend>

        <div>
            <label for="account-password" class="block text-sm font-medium">New password</label>
            <input id="account-password" type="password" wire:model="password" autocomplete="new-password"
                   class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
            @error('password') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="account-password-confirmation" class="block text-sm font-medium">Confirm new password</label>
            <input id="account-password-confirmation" type="password" wire:model="password_confirmation" autocomplete="new-password"
                   class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
        </div>
    </fieldset>

    <fieldset class="space-y-5 rounded-xl border border-line bg-white/[0.02] p-6">
        <legend class="px-2 text-xs font-mono uppercase tracking-[0.2em] text-fg-dim">Confirm changes</legend>

        <div>
            <label for="account-current-password" class="block text-sm font-medium">Current password</label>
            <input id="account-current-password" type="password" wire:model="current_password" autocomplete="current-password"
                   class="mt-2 w-full rounded-lg border border-line bg-white/5 px-3.5 py-2.5 text-sm text-fg focus:border-brand-500 focus:outline-none">
            @error('current_password') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
        </div>
    </fieldset>

    <div class="flex justify-end">
        <x-button type="button" wire:click="save" wire:loading.attr="disabled" wire:target="save">
            <span wire:loading.remove wire:target="save">Save account</span>
            <span wire:loading wire:target="save">Saving…</span>
        </x-button>
    </div>
</div>