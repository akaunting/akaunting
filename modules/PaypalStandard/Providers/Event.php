<?php

namespace Modules\PaypalStandard\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as Provider;
use Modules\PaypalStandard\Listeners\ShowAsPaymentMethod;

class Event extends Provider
{
    /**
     * The event listener mappings for the module.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\Module\PaymentMethodShowing::class => [
            ShowAsPaymentMethod::class,
        ],
    ];
}
