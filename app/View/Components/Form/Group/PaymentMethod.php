<?php

namespace App\View\Components\Form\Group;

use App\Abstracts\View\Components\Form;
use App\Utilities\Modules;

class PaymentMethod extends Form
{
    public $type = 'payment_method';

    public $payment_methods;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        if (empty($this->name)) {
            $this->name = 'payment_method';
        }

        $this->payment_methods = Modules::getPaymentMethods();

        if ($this->selected === null && empty($this->getParentData('model'))) {
            $this->selected = setting('default.payment_method');
        }

        return view('components.form.group.payment_method');
    }
}
