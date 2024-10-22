<?php

namespace App\View\Components\Form\Group;

use App\Abstracts\View\Components\Form;

class PaymentTerm extends Form
{
    public $type = 'payment-term';

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $this->name = 'temp_payment_term';

        $payment_terms = [
            '0'  => trans('settings.invoice.due_receipt'),
            '7'  => trans('settings.invoice.due_days', ['days' => 7]),
            '15' => trans('settings.invoice.due_days', ['days' => 15]),
            '30' => trans('settings.invoice.due_days', ['days' => 30]),
            '45' => trans('settings.invoice.due_days', ['days' => 45]),
            '60' => trans('settings.invoice.due_days', ['days' => 60]),
            '90' => trans('settings.invoice.due_days', ['days' => 90]),
            'custom' => trans('settings.invoice.due_custom'),
        ];

        if (empty($this->options)) {
            $this->options = $this->getOptions($payment_terms);
        }

        if (! array_key_exists($this->selected, $payment_terms)) {
            $this->value = $this->selected;
            $this->selected = 'custom';
        }

        return view('components.form.group.payment_term');
    }
}
