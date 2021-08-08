<?php

namespace App\Http\Livewire\Common\Notifications;

use App\Traits\Modules;
use App\Utilities\Date;
use Livewire\Component;

class NewApps extends Component
{
    use Modules;

    public function markRead($alias)
    {
        $notifications = $this->getNotifications('new-apps');

        foreach ($notifications as $notification) {
            if ($notification->alias != $alias) {
                continue;
            }

            $read = $notification;
        }

        $prefix = 'notifications.' . user()->id . '.' . $alias;

        setting()->set([
            $prefix . '.name'       => $read->name,
            $prefix . '.message'    => $read->alias,
            $prefix . '.date'       => Date::now(),
            $prefix . '.status'     => '0',
        ]);

        setting()->save();

        $this->dispatchBrowserEvent('mark-read', [
            'type' => 'new-apps',
            'message' => trans('notifications.messages.mark_read', ['type' => $read->name]),
        ]);
    }

    public function markReadAll()
    {
        $notifications = $this->getNotifications('new-apps');

        foreach ($notifications as $notification) {
            $prefix = 'notifications.' . user()->id . '.' . $notification->alias;

            setting()->set([
                $prefix . '.name'       => $notification->name,
                $prefix . '.message'    => $notification->alias,
                $prefix . '.date'       => Date::now(),
                $prefix . '.status'     => '0',
            ]);
        }

        setting()->save();

        $this->dispatchBrowserEvent('mark-read-all', [
            'type' => 'new-apps',
            'message' => trans('notifications.messages.mark_read_all', ['type' => trans_choice('notifications.new_apps', 2)]),
        ]);
    }

    public function render()
    {
        $notifications = $this->getNotifications('new-apps');

        $this->clearReadNotifications($notifications);

        return view('livewire.common.notifications.new-apps', compact('notifications'));
    }

    protected function clearReadNotifications(&$notifications)
    {
        $hide_notifications = setting('notifications.' . user()->id);

        if (!$hide_notifications) {
            return;
        }

        if (!$notifications) {
            return;
        }

        $aliases = [];

        // MarkRead app notification
        foreach ($notifications as $index => $notification) {
            $aliases[] = $notification->alias;

            if (setting('notifications.' . user()->id . '.' . $notification->alias)) {
                unset($notifications[$index]);
            }
        }

        // Clear setting table missing notification
        foreach ($hide_notifications as $alias => $hide_notification) {
            if (in_array($alias, $aliases)) {
                continue;
            }

            setting()->forget('notifications.' . user()->id . '.' . $alias);
            setting()->save();
        }
    }
}
