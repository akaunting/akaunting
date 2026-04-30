<?php

namespace App\View\Components\Index;

use App\Abstracts\View\Component;

class Balance extends Component
{
    /**
     * The balance amount.
     *
     * @var float
     */
    public $amount;

    /**
     * The text color class.
     *
     * @var string
     */
    public $textColor;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($amount = 0) {
        $this->amount = $this->getAmount($amount);
        $this->textColor = $this->getTextColor($amount);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.index.balance');
    }

    /**
     * Formats the amount according to the location context.
     *
     * @param float $amount
     * @return string
     */
    protected function getAmount($amount)
    {
        return money($amount, setting('default.currency'), true);
    }

    /**
     * Gets the class of color considering given amount.
     *
     * @param float $amount
     * @return string|null
     */
    protected function getTextColor($amount)
    {
        switch ($amount) {
            case $amount > 0:
                return 'text-green';
            case $amount < 0:
                return 'text-red';
            default:
                return '';
        }
    }
}
