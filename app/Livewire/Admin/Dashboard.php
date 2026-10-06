<?php

namespace App\Livewire\Admin;

use App\Services\AdminStatsService;
use Livewire\Component;

class Dashboard extends Component
{
    public function render(AdminStatsService $stats)
    {
        return view('livewire.admin.dashboard', [
            'stats' => $stats->snapshot(),
        ])->layout('components.layouts.admin', ['title' => 'Dashboard']);
    }
}
