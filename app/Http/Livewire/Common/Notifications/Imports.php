<?php

namespace App\Http\Livewire\Common\Notifications;

use Livewire\Component;
use Livewire\WithPagination;

class Imports extends Component
{
    use WithPagination;

    public function render()
    {
        $limit = 5;

        $notifications = user()->notifications()->unread()
            ->where('type', 'App\Notifications\Common\ImportCompleted')
            ->orWhere('type', 'App\Notifications\Common\ImportFailed')
            ->paginate($limit);

        return view('livewire.common.notifications.imports', compact('notifications'));
    }

    public function paginationView()
    {
        return 'vendor.livewire.default';
    }
}
