<?php

namespace App\View\Components\Widgets;

use App\Abstracts\View\Component;

class LastPayment extends Component
{
    public $contact;

    public $model;

    public $payment;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $model = false, $payment = false, $contact = false
    ) {
        $this->contact = ! empty($contact) ? $contact : user()->contact;
        $this->model = $model;
        $this->payment = $this->getPayment($model, $payment);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.widgets.last_payment');
    }

    protected function getPayment($model, $payment)
    {
        if (! empty($model)) {
            return $model;
        }

        if (! empty($payment)) {
            return $payment;
        }

        return $this->contact->income_transactions()->orderBy('created_at', 'desc')->first();
    }
}
