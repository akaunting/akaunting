<?php

namespace Modules\OfflinePayments\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as Provider;
use Modules\OfflinePayments\Listeners\FinishInstallation;
use Modules\OfflinePayments\Listeners\ShowAsPaymentMethod;
use Modules\OfflinePayments\Listeners\ShowInSettingsPage;

class Event extends Provider
{
    /**
     * The event listener mappings for the module.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\Module\Installed::class => [
            FinishInstallation::class,
        ],
        \App\Events\Module\PaymentMethodShowing::class => [
            ShowAsPaymentMethod::class,
        ],
        \App\Events\Module\SettingShowing::class => [
            ShowInSettingsPage::class,
        ],
    ];
}
