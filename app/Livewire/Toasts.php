<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class Toasts extends Component
{
    /** @var array<int, array{id: string, type: string, message: string}> */
    public array $toasts = [];

    #[On('toast')]
    public function push(string $type = 'success', string $message = ''): void
    {
        if ($message === '') {
            return;
        }

        $id = (string) str()->uuid();
        $this->toasts[] = compact('id', 'type', 'message');
    }

    public function dismiss(string $id): void
    {
        $this->toasts = array_values(array_filter(
            $this->toasts,
            fn ($t) => $t['id'] !== $id,
        ));
    }

    public function render()
    {
        return view('livewire.toasts');
    }
}
