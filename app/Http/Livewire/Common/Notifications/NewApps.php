<?php

namespace App\Http\Livewire\Common\Notifications;

use App\Traits\Modules;
use Livewire\Component;

class NewApps extends Component
{
    use Modules;

    public function render()
    {
        $notifications = $this->getNotifications('new-apps');

        return view('livewire.common.notifications.new-apps', compact('notifications'));
    }
}
