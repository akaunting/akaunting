<?php

namespace App\Http\ViewComposers;

use Route;
use Illuminate\View\View;
use App\Traits\Modules as RemoteModules;

class Notifications
{
    use RemoteModules;

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // No need to add suggestions in console
        if (app()->runningInConsole() || !env('APP_INSTALLED')) {
            return;
        }

        $path = Route::current()->uri();

        if (empty($path)) {
            return;
        }

        $notifications = $this->getNotifications($path);

        if (empty($notifications)) {
            return;
        }

        // Push to a stack
        foreach ($notifications as $notification) {
            $setting = 'notifications.'. $notification->path . '.' . $notification->id . '.status';

            $path = str_replace('/', '#', $notification->path);

            $message = str_replace('#path#', $path, $notification->message);
            $message = str_replace('#token#', csrf_token(), $message);

            if (setting($setting, 1)) {
                $view->getFactory()->startPush('content_content_start', $message);
            }
        }
    }
}
