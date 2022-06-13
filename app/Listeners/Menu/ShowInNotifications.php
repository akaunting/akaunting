<?php

namespace App\Listeners\Menu;

use App\Events\Menu\NotificationsCreated as Event;
use App\Traits\Modules;
use App\Utilities\Versions;
use Illuminate\Notifications\DatabaseNotification;

class ShowInNotifications
{
    use Modules;

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        if (user()->cannot('read-notifications')) {
            return;
        }

        // Notification tables
        $notifications = collect();

        // Update notifications
        if (user()->can('read-install-updates')) {
            $updates = Versions::getUpdates();

            foreach ($updates as $key => $update) {
                $prefix = ($key == 'core') ? 'core' : 'module';

                $new = new DatabaseNotification();
                $new->id = $key;
                $new->type = 'updates';
                $new->notifiable_type = "users";
                $new->notifiable_id = user()->id;
                $new->data = [
                    'title' => $key . ' (v' . $update . ')',
                    'description' => '<a href="' . route('updates.index') . '">' . trans('install.update.' . $prefix) . '</a>',
                ];
                $new->created_at = \Carbon\Carbon::now();

                $notifications->push($new);
            }
        }

        // New app notifcations
        $new_apps = $this->getNotifications('new-apps');

        foreach ($new_apps as $key => $new_app) {
            if (setting('notifications.' . user()->id . '.' . $new_app->alias)) {
                unset($new_apps[$key]);

                continue;
            }

            $new = new DatabaseNotification();
            $new->id = $key;
            $new->type = 'new-apps';
            $new->notifiable_type = "users";
            $new->notifiable_id = user()->id;
            $new->data = [
                'title' => $new_app->name,
                'description' => '', // $new_app->message,
                'alias' => $new_app->alias,
            ];
            $new->created_at = $new_app->started_at->date;

            $notifications->push($new);
        }

        $unReadNotifications = user()->unReadNotifications;

        foreach ($unReadNotifications as $unReadNotification) {
            $notifications->push($unReadNotification);
        }

        $event->notifications->notifications = $notifications;
    }
}
