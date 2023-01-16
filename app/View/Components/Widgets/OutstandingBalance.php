<?php

namespace App\View\Components\Widgets;

use App\Abstracts\View\Component;
use Illuminate\Support\Str;

class OutstandingBalance extends Component
{
    public $contact;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $contact = false
    ) {
        $this->contact = ! empty($contact) ? $contact : user()->contact;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.widgets.outstanding_balance');
    }
}
