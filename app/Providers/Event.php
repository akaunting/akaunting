<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as Provider;

class Event extends Provider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Install\UpdateFinished' => [
            'App\Listeners\Update\CreateModuleUpdatedHistory',
            'App\Listeners\Update\V20\Version200',
        ],
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\Auth\Login',
        ],
        'Illuminate\Auth\Events\Logout' => [
            'App\Listeners\Auth\Logout',
        ],
        'App\Events\Purchase\BillCreated' => [
            'App\Listeners\Purchase\CreateBillCreatedHistory',
        ],
        'App\Events\Purchase\BillReceived' => [
            'App\Listeners\Purchase\MarkBillReceived',
        ],
        'App\Events\Purchase\BillRecurring' => [
            'App\Listeners\Purchase\SendBillRecurringNotification',
        ],
        'App\Events\Sale\PaymentReceived' => [
            'App\Listeners\Sale\CreateInvoiceTransaction',
            'App\Listeners\Sale\SendInvoicePaymentNotification',
        ],
        'App\Events\Sale\InvoiceCreated' => [
            'App\Listeners\Sale\CreateInvoiceCreatedHistory',
            'App\Listeners\Sale\IncreaseNextInvoiceNumber',
        ],
        'App\Events\Sale\InvoiceSent' => [
            'App\Listeners\Sale\MarkInvoiceSent',
        ],
        'App\Events\Sale\InvoiceViewed' => [
            'App\Listeners\Sale\MarkInvoiceViewed',
        ],
        'App\Events\Sale\InvoiceRecurring' => [
            'App\Listeners\Sale\SendInvoiceRecurringNotification',
        ],
        'App\Events\Menu\AdminCreated' => [
            'App\Listeners\Menu\AddAdminItems',
        ],
        'App\Events\Menu\PortalCreated' => [
            'App\Listeners\Menu\AddPortalItems',
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        'App\Listeners\Common\AddDateToReports',
        'App\Listeners\Common\AddAccountsToReports',
        'App\Listeners\Common\AddCustomersToReports',
        'App\Listeners\Common\AddVendorsToReports',
        'App\Listeners\Common\AddExpenseCategoriesToReports',
        'App\Listeners\Common\AddIncomeCategoriesToReports',
        'App\Listeners\Common\AddSearchToReports',
    ];
}
