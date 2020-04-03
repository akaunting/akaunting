<?php

namespace Modules\OfflinePayments\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as Provider;
use Modules\OfflinePayments\Listeners\InstallModule;
use Modules\OfflinePayments\Listeners\ShowPaymentMethod;
use Modules\OfflinePayments\Listeners\ShowSetting;

class Event extends Provider
{
    /**
     * The event listener mappings for the module.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\Module\Installed::class => [
            InstallModule::class,
        ],
        \App\Events\Module\PaymentMethodShowing::class => [
            ShowPaymentMethod::class,
        ],
        \App\Events\Module\SettingShowing::class => [
            ShowSetting::class,
        ],
    ];
}
