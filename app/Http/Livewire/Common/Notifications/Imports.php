<?php

namespace App\Http\Livewire\Common\Notifications;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Collection;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;

class Imports extends Component
{
    use WithPagination;

    protected $listeners = [
        'refreshParent' => '$notifications',
    ];

    public function markRead($notification_id)
    {
        $notification = DatabaseNotification::find($notification_id);
        $data = $notification->getAttribute('data');

        $notification->markAsRead();

        $this->dispatchBrowserEvent('mark-read', [
            'type' => 'import',
            'message' => trans('notifications.messages.mark_read', ['type' => $data['translation']]),
        ]);
    }

    public function markReadAll()
    {
        $notifications = $this->getNotifications();

        foreach ($notifications as $notification) {
            $notification->markAsRead();
        }

        $this->dispatchBrowserEvent('mark-read-all', [
            'type' => 'import',
            'message' => trans('notifications.messages.mark_read_all', ['type' => trans('import.import')]),
        ]);
    }

    public function render()
    {
        $limit = 5;

        $notifications = $this->getNotifications($limit);

        return view('livewire.common.notifications.imports', compact('notifications'));
    }

    protected function getNotifications($limit = false)
    {
        $query = user()->notifications()->unread()
            ->where('type', 'App\Notifications\Common\ImportCompleted')
            ->orWhere('type', 'App\Notifications\Common\ImportFailed');

        if ($limit) {
            $notifications = $query->paginate($limit);
        } else {
            $notifications = $query->get();
        }

        if ($notifications->items()) {
            $items = [];

            foreach ($notifications->items() as $key => $notification) {
                $data = (object) $notification->getAttribute('data');
                $data->notification_id = $notification->getAttribute('id');
    
                $items[] = $data;
            }

            $notifications->setCollection(Collection::make($items));
        }

        return $notifications;
    }

    public function paginationView()
    {
        return 'vendor.livewire.default';
    }
}
