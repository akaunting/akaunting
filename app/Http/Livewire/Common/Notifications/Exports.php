<?php

namespace App\Http\Livewire\Common\Notifications;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Collection;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;

class Exports extends Component
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

        $type = isset($data['file_name']) ?: trans('general.export');

        $this->dispatchBrowserEvent('mark-read', [
            'type' => 'export',
            'message' => trans('notifications.messages.mark_read', ['type' => $type]),
        ]);
    }

    public function markReadAll()
    {
        $notifications = $this->getNotifications();

        foreach ($notifications as $notification) {
            $notification->markAsRead();
        }

        $this->dispatchBrowserEvent('mark-read-all', [
            'type' => 'export',
            'message' => trans('notifications.messages.mark_read_all', ['type' => trans('general.export')]),
        ]);
    }

    public function render()
    {
        $limit = 5;

        $notifications = $this->getNotifications($limit);

        return view('livewire.common.notifications.exports', compact('notifications'));
    }

    protected function getNotifications($limit = false)
    {
        $query = user()->notifications()->unread()
            ->where(function ($query) {
                $query->where('type', 'App\Notifications\Common\ExportCompleted')
                    ->orWhere('type', 'App\Notifications\Common\ExportFailed');
            });

        if ($limit) {
            $notifications = $query->paginate($limit);
        } else {
            $notifications = $query->get();
        }

        if ($notifications) {
            $items = [];

            foreach ($notifications as $key => $notification) {
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
