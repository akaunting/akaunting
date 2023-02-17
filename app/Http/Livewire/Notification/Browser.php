<?php

namespace App\Http\Livewire\Notification;

use Cookie;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;

class Browser extends Component
{
    public $status = false;

    public function render(): View
    {
        $user_agent = request()->header('User-Agent');

        $view = 'livewire.notification.browser.index';

        if (Str::contains($user_agent, 'Firefox')) {
            $view = 'livewire.notification.browser.firefox';

            if ($this->status || ! empty($_COOKIE['firefox-icon-notification-confirm'])) {
                $this->status = true;
            } else {
                $this->status = false;
            }
        } elseif (Str::contains($user_agent, 'Edg')) {
            // $view = 'livewire.notification.browser.edge';
        } elseif (Str::contains($user_agent, 'Safari')) {
            // $view = 'livewire.notification.browser.safari';
        } elseif (Str::contains($user_agent, 'Chrome')) {
            // $view = 'livewire.notification.browser.chrome';
        } elseif (Str::contains($user_agent, 'Opera')) {
            // $view = 'livewire.notification.browser.opera';
        }

        return view($view);
    }

    public function firefoxConfirm()
    {
        $expire = time() + (86400 * 30 * 30 * 30);

        setcookie('firefox-icon-notification-confirm', true, $expire, '/');

        $this->status = true;
    }
}
