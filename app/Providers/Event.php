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
            'App\Listeners\Update\V20\Version203',
            'App\Listeners\Update\V20\Version205',
            'App\Listeners\Update\V20\Version207',
            'App\Listeners\Update\V20\Version208',
            'App\Listeners\Update\V20\Version209',
        ],
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\Auth\Login',
        ],
        'Illuminate\Auth\Events\Logout' => [
            'App\Listeners\Auth\Logout',
        ],
        'App\Events\Purchase\BillCreated' => [
            'App\Listeners\Purchase\CreateBillCreatedHistory',
            'App\Listeners\Purchase\IncreaseNextBillNumber',
        ],
        'App\Events\Purchase\BillReceived' => [
            'App\Listeners\Purchase\MarkBillReceived',
        ],
        'App\Events\Purchase\BillCancelled' => [
            'App\Listeners\Purchase\MarkBillCancelled',
        ],
        'App\Events\Purchase\BillRecurring' => [
            'App\Listeners\Purchase\SendBillRecurringNotification',
        ],
        'App\Events\Purchase\BillReminded' => [
            'App\Listeners\Purchase\SendBillReminderNotification',
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
        'App\Events\Sale\InvoiceCancelled' => [
            'App\Listeners\Sale\MarkInvoiceCancelled',
        ],
        'App\Events\Sale\InvoiceViewed' => [
            'App\Listeners\Sale\MarkInvoiceViewed',
        ],
        'App\Events\Sale\InvoiceRecurring' => [
            'App\Listeners\Sale\SendInvoiceRecurringNotification',
        ],
        'App\Events\Sale\InvoiceReminded' => [
            'App\Listeners\Sale\SendInvoiceReminderNotification',
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
        'App\Listeners\Report\AddDate',
        'App\Listeners\Report\AddAccounts',
        'App\Listeners\Report\AddCustomers',
        'App\Listeners\Report\AddVendors',
        'App\Listeners\Report\AddExpenseCategories',
        'App\Listeners\Report\AddIncomeCategories',
        'App\Listeners\Report\AddIncomeExpenseCategories',
        'App\Listeners\Report\AddSearch',
        'App\Listeners\Report\AddRowsToTax',
    ];
}
