<?php

namespace App\View\Components\Widgets;

use App\Abstracts\View\Component;
use Illuminate\Support\Str;

class Contact extends Component
{
    public $model;

    public $contact;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $model = false, $contact = false
    ) {
        $this->model = ! empty($model) ? $model : user()->contact;
        $this->contact = ! empty($contact) ? $contact : $this->model;
    }

    /**
     * Determine if the given full_name is the currently selected full_name.
     *
     * @param  string  $full_name
     * @return string
     */
    public function shortName($full_name)
    {
        if (empty($full_name)) {
            return trans('general.na');
        }

        $names = explode(' ', $full_name);

        return strtoupper(substr($names[0], 0, 1) . substr(end($names), 0, 1));
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.widgets.contact');
    }
}
