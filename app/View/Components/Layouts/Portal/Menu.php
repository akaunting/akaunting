<?php

namespace App\View\Components\Layouts\Portal;

use App\Abstracts\View\Component;

class Menu extends Component
{
    /** array */
    public $companies = [];

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $this->companies = $this->getCompanies();

        return view('components.layouts.portal.menu');
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
