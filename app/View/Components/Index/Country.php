<?php

namespace App\View\Components\Index;

use App\Abstracts\View\Component;

class Country extends Component
{
    /**
     * The Country country.
     *
     * @var string
     */
    public $country;

    /**
     * The Country code.
     *
     * @var string
     */
    public $code;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($code) {
        $this->code = $code;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $this->country = trans('countries.' . $this->code);

        return view('components.index.country');
    }
}
