<?php

namespace App\View\Components\Index;

use App\Abstracts\View\Component;

class Currency extends Component
{
    /**
     * The Currency currency.
     *
     * @var string
     */
    public $currency;

    /**
     * The Currency code.
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
        $code = ($this->code) ? $this->code : default_currency();

        $this->currency = config('money.currencies.' . $code . '.name');

        return view('components.index.currency');
    }
}
