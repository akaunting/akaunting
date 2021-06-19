<?php

namespace App\Http\Livewire\Common\Notifications;

use App\Abstracts\Livewire\Document as Component;
use Livewire\WithPagination;
use Illuminate\Support\Collection;

class Recurring extends Component
{
    use WithPagination;

    public function render()
    {
        $limit = 5;
        $documents = user()->notifications()->unread()->where('type', 'App\Notifications\Sale\Invoice')->paginate($limit);

        $documents->setCollection(Collection::make([]));

        return view('livewire.common.notifications.recurring', compact('documents'));
    }

    public function paginationView()
    {
        return 'vendor.livewire.default';
    }
}
