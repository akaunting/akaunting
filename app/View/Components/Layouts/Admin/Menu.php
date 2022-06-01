<?php

namespace App\View\Components\Layouts\Admin;

use App\Abstracts\View\Component;
use App\Utilities\Versions;

class Menu extends Component
{
    /** array */
    public $companies = [];

    public $notification_count;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $this->companies = $this->getCompanies();

        $version_update = Versions::getUpdates();

        $this->notification_count = user()->unreadNotifications->count();
        $this->notification_count += count($version_update);

        return view('components.layouts.admin.menu');
    }

    public function getCompanies()
    {
        if ($user = user()) {
            $companies = $user->companies()->enabled()->limit(10)->get()->sortBy('name');
        } else {
            $companies = [];
        }

        return $companies;
    }
}
