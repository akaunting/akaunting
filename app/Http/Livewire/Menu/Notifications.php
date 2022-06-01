<?php

namespace App\Http\Livewire\Menu;

use App\Events\Menu\NotificationsCreated;
use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;
use Livewire\Component;

class Notifications extends Component
{
    public $user = null;

    public $keyword = '';

    public $notifications = [];

    protected $listeners = ['resetKeyword'];

    public function render(): View
    {
        $this->user = user();

        $this->notifications = $this->getNotifications();

        return view('livewire.menu.notifications');
    }

    public function markRead($notification_id)
    {
        $notification = DatabaseNotification::find($notification_id);
        $data = $notification->getAttribute('data');

        $notification->markAsRead();

        $type = isset($data['file_name']) ?: trans('general.export');

        $this->dispatchBrowserEvent('mark-read', [
            'type' => 'notification',
            'message' => trans('notifications.messages.mark_read', ['type' => $type]),
        ]);
    }

    public function markReadAll()
    {
        $notifications = user()->unreadNotifications;

        foreach ($notifications as $notification) {
            $notification->markAsRead();
        }

        $this->dispatchBrowserEvent('mark-read-all', [
            'type' => 'notification',
            'message' => trans('notifications.messages.mark_read_all', ['type' => trans('general.export')]),
        ]);
    }

    public function getNotifications(): array
    {
        $notifications = new \stdClass();
        $notifications->notifications = [];
        $notifications->keyword = $this->keyword;

        event(new NotificationsCreated($notifications));

        $rows = [];

        foreach ($notifications->notifications as $notification) {
            if (! $this->availableInSearch($notification)) {
                continue;
            }

            $rows[] = $notification;
        }

        return $rows;
    }

    public function availableInSearch($notification): bool
    {
        if (empty($this->keyword)) {
            return true;
        }

        return $this->search($notification);
    }

    public function search($notification): bool
    {
        $status = false;

        $keywords = explode(' ', $this->keyword);

        foreach ($keywords as $keyword) {
            if (Str::contains(Str::lower($notification->data['title']), Str::lower($keyword))) {
                $status = true;

                break;
            }

            if (
                !empty($notification->data['description'])
                && Str::contains(Str::lower($notification->data['description']), Str::lower($keyword))
            ) {
                $status = true;

                break;
            }
        }

        return $status;
    }

    public function resetKeyword(): void
    {
        $this->keyword = '';
    }
}
