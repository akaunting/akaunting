<?php

namespace App\Listeners\Menu;

use App\Events\Menu\NotificationsCreated as Event;
use App\Models\Common\Notification;
use App\Traits\Modules;
use App\Utilities\Date;
use App\Utilities\Versions;

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

        static $notifications;

        if (! empty($notifications)) {
            $event->notifications->notifications = $notifications;

            return;
        }

        // Notification tables
        $notifications = collect();

        // Update notifications
        if (user()->can('read-install-updates')) {
            $updates = Versions::getUpdates();

            foreach ($updates as $key => $update) {
                $prefix = ($key == 'core') ? 'core' : 'module';

                if ($prefix == 'module' && ! module($key)) {
                    continue;
                }

                $name = ($prefix == 'core') ? 'Akaunting' : module($key)?->getName();

                $new = new Notification();
                $new->id = $key;
                $new->type = 'updates';
                $new->notifiable_type = "users";
                $new->notifiable_id = user()->id;
                $new->data = [
                    'title' => $name . ' (v' . $update?->latest . ')',
                    'description' => trans('install.update.' . $prefix, ['module' => $name, 'url' => route('updates.index')]),
                ];
                $new->created_at = Date::now();

                $notifications->push($new);
            }
        }

        // New app notifications
        $new_apps = $this->getNotifications('new-apps');

        foreach ($new_apps as $key => $new_app) {
            if (setting('notifications.' . user()->id . '.' . $new_app->alias)) {
                unset($new_apps[$key]);

                continue;
            }

            $app_url = route('apps.app.show', [
                'alias'         => $new_app->alias,
                'utm_source'    => 'notification',
                'utm_medium'    => 'app',
                'utm_campaign'  => str_replace('-', '_', $new_app->alias),
            ]);

            $new = new Notification();
            $new->id = $key;
            $new->type = 'new-apps';
            $new->notifiable_type = "users";
            $new->notifiable_id = user()->id;
            $new->data = [
                'title' => $new_app->name,
                'description' => trans('notifications.new_apps', ['app' => $new_app->name, 'url' => $app_url]),
                'alias' => $new_app->alias,
            ];
            $new->created_at = $new_app->started_at->date;

            $notifications->push($new);
        }

        foreach (user()->unreadNotifications as $unreadNotification) {
            $notifications->push($unreadNotification);
        }

        $event->notifications->notifications = $notifications;
    }
}
