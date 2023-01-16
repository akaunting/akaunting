<?php

namespace App\View\Components\Layouts\Admin;

use App\Abstracts\View\Component;
use App\Traits\Modules;
use Illuminate\Support\Facades\Route;

class Notifications extends Component
{
    use Modules;

    public $notifications;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $this->notifications = $this->getNotify();

        return view('components.layouts.admin.notifications');
    }

    public function getNotify()
    {
        if (! $path = Route::current()->uri()) {
            return [];
        }

        $path = str_replace('{company_id}/', '', $path);
        $path = str_replace('{company_id}', '', $path);

        $notify = [];
        $notifications = $this->getNotifications($path);

        // Push to a stack
        foreach ($notifications as $notification) {
            $path = str_replace('/', '#', $notification->path);

            $message = str_replace('#path#', $path, $notification->message);
            $message = str_replace('#token#', csrf_token(), $message);
            $message = str_replace('#url#', route('dashboard'), $message);
            $message = str_replace('#company_id#', company_id(), $message);

            if (! setting('notifications.' . $notification->path . '.' . $notification->id . '.status', 1)) {
                continue;
            }

            $notify[] = $message;
        }

        return $notify;
    }
}
