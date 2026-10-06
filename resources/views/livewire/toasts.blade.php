<div class="pointer-events-none fixed bottom-6 right-6 z-50 flex w-80 flex-col gap-2"
     aria-live="polite" aria-atomic="true">
    @foreach ($toasts as $toast)
        @php
            $styles = [
                'success' => 'border-emerald-500/40 bg-emerald-500/10 text-emerald-300',
                'error' => 'border-red-500/40 bg-red-500/10 text-red-300',
                'info' => 'border-brand-500/40 bg-brand-500/10 text-brand-300',
            ][$toast['type']] ?? 'border-line bg-white/5 text-fg';
        @endphp

        <div wire:key="{{ $toast['id'] }}"
             x-data="{ show: false }"
             x-init="$nextTick(() => show = true); setTimeout(() => { show = false; $wire.dismiss('{{ $toast['id'] }}') }, 4000)"
             x-show="show"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-end="opacity-0 translate-y-2"
             class="pointer-events-auto rounded-lg border px-4 py-3 text-sm backdrop-blur {{ $styles }}">
            {{ $toast['message'] }}
        </div>
    @endforeach
</div>