@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex items-center justify-between">
        <div class="text-xs text-fg-dim">
            Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }}
        </div>

        <div class="flex items-center gap-1">
            @if ($paginator->onFirstPage())
                <span class="rounded-md border border-line px-3 py-1.5 text-xs text-fg-dim">Previous</span>
            @else
                <button wire:click="previousPage" wire:loading.attr="disabled"
                        class="rounded-md border border-line px-3 py-1.5 text-xs text-fg-muted hover:text-fg">
                    Previous
                </button>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-2 text-xs text-fg-dim">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="rounded-md border border-brand-500/40 bg-brand-500/10 px-3 py-1.5 text-xs text-brand-300">{{ $page }}</span>
                        @else
                            <button wire:click="gotoPage({{ $page }})"
                                    class="rounded-md border border-line px-3 py-1.5 text-xs text-fg-muted hover:text-fg">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <button wire:click="nextPage" wire:loading.attr="disabled"
                        class="rounded-md border border-line px-3 py-1.5 text-xs text-fg-muted hover:text-fg">
                    Next
                </button>
            @else
                <span class="rounded-md border border-line px-3 py-1.5 text-xs text-fg-dim">Next</span>
            @endif
        </div>
    </nav>
@endif