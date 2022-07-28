<?php

namespace App\Listeners\Menu;

use App\Events\Menu\ProfileCreated as Event;
use App\Traits\Permissions;

class ShowInProfile
{
    use Permissions;

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        $menu = $event->menu;

        $title = trim(trans('auth.profile'));
        if ($this->canAccessMenuItem($title, 'read-auth-profile')) {
            $menu->route('profile.edit', $title, [user()->id], 10, ['icon' => 'badge']);
        }

        if (user()->isCustomer()) {
            $menu->route('portal.profile.edit', $title, [user()->id], 10, ['icon' => 'badge']);
        }

        $title = trim(trans_choice('general.users', 2));
        if ($this->canAccessMenuItem($title, 'read-auth-users')) {
            $menu->route('users.index', $title, [], 20, ['icon' => 'people']);
        }

        $is_portal = user()->isCustomer() ? 'portal.' : '';

        $title = trim(trans('auth.logout'));
        $menu->route($is_portal . 'logout', $title, [], 90, ['icon' => 'power_settings_new', 'class' => 'mt-5']);
    }
}
