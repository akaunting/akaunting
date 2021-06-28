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
            'type' => 'reminder-' . $this->type,
            'message' => trans('notifications.messages.mark_read', ['type' => $data[$this->type . '_number']]),
        ]);
    }

    public function markReadAll()
    {
        $notifications = $this->getNotifications();

        foreach ($notifications as $notification) {
            $notification->markAsRead();
        }

        $this->dispatchBrowserEvent('mark-read-all', [
            'type' => 'reminder-' . $this->type,
            'message' => trans('notifications.messages.mark_read', ['type' => trans_choice('general.' . Str::plural($this->type) , 2)]),
        ]);
    }

    public function render()
    {
        $limit = 5;

        $notifications = $this->getNotifications($limit);

        return view('livewire.common.notifications.reminder', compact('notifications'));
    }

    protected function getNotifications($limit = false)
    {
        $type = config('type.' . $this->type . '.notification.class');

        $query = user()->notifications()->unread()
            ->where('type', $type)
            ->where('data', 'like', '%template_alias:{$this->type}_remind_admin%');

        if ($limit) {
            $notifications = $query->paginate($limit);
        } else {
            $notifications = $query->get();
        }

        if ($notifications) {
            $items = [];

            foreach ($notifications as $key => $notification) {
                $data = (object) $notification->getAttribute('data');

                $item = Document::{$this->type}()->where('id', $data[$this->type . '_id'])->first();
                $item->notification_id = $notification->getAttribute('id');
    
                $items[] = $item;
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
