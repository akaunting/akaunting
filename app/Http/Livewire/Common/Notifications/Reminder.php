<?php

namespace App\Http\Livewire\Common\Notifications;

use App\Abstracts\Livewire\Document as Component;
use App\Models\Document\Document;
use Livewire\WithPagination;
use Illuminate\Support\Collection;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;

class Reminder extends Component
{
    use WithPagination;

    public function markRead($notification_id)
    {
        $notification = DatabaseNotification::find($notification_id);
        $data = $notification->getAttribute('data');

        $notification->markAsRead();

        $this->dispatchBrowserEvent('mark-read', [
            'type' => $this->type,
            'message' => trans('notifications.messages.mark_read', ['type' => $data[$this->type . '_number']]),
        ]);
    }

    public function markReadAll()
    {
        $type = config('type.' . $this->type . '.notification.class');

        $notifications = user()->notifications()->unread()
            ->where('type', $type)
            ->get();

        foreach ($notifications as $notification) {
            $notification->markAsRead();
        }

        $this->dispatchBrowserEvent('mark-read-all', [
            'type' => $this->type,
            'message' => trans('notifications.messages.mark_read', ['type' => trans_choice('general.' . Str::plural($this->type) , 2)]),
        ]);
    }

    public function render()
    {
        $limit = 10;

        $type = config('type.' . $this->type . '.notification.class');

        $documents = user()->notifications()->unread()
            ->where('type', $type)
            ->paginate($limit);

        $items = [];

        foreach ($documents->items() as $key => $document) {
            $data = $document->getAttribute('data');

            $item = Document::invoice()->where('id', $data['invoice_id'])->first();

            $item->notification_id = $document->getAttribute('id');

            $items[] = $item;
        }

        $documents->setCollection(Collection::make($items));

        return view('livewire.common.notifications.reminder', compact('documents'));
    }

    public function paginationView()
    {
        return 'vendor.livewire.default';
    }
}
