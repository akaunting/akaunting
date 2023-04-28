<?php

namespace App\View\Components;

use App\Abstracts\View\Component;
use App\Utilities\Modules;
use Illuminate\Support\Str;

class PaymentMethod extends Component
{
    public $code, $method;

    public $payment_methods;

    public $payment_method;

    public $type;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $code = null, $method = null, string $payment_method = null, array $payment_methods = [], $type = null
    ) {
        $this->code = $code;
        $this->method = $method;
        $this->type = $type;

        $this->payment_methods = $this->getPaymentMethods($payment_methods, $type);
        $this->payment_method = $this->getPaymentMethod($payment_method, $code);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.payment_method');
    }

    protected function getPaymentMethods($payment_methods, $type)
    {
        if (! empty($payment_methods)) {
            return $payment_methods;
        }

        // check here protal or admin panel..
        if (empty($type)) {
            $type = Str::contains(request()?->route()?->getName(), 'portal') ? 'customer' : 'all';
        }

        $payment_methods = Modules::getPaymentMethods($type);

        return $payment_methods;
    }

    protected function getPaymentMethod($payment_method, $code)
    {
        if (! empty($payment_methods)) {
            return $payment_methods;
        }

        if (! empty($this->payment_methods[$code])) {
            return $this->payment_methods[$code];
        }

        if (! empty($this->payment_methods[$this->method])) {
            return $this->payment_methods[$this->method];
        }

        if (! empty($this->payment_methods[$payment_method])) {
            return $this->payment_methods[$payment_method];
        }

        return false;
    }
}
