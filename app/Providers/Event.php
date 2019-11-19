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
            'App\Listeners\Update\V10\Version106',
            'App\Listeners\Update\V10\Version107',
            'App\Listeners\Update\V10\Version108',
            'App\Listeners\Update\V10\Version109',
            'App\Listeners\Update\V11\Version110',
            'App\Listeners\Update\V11\Version112',
            'App\Listeners\Update\V11\Version113',
            'App\Listeners\Update\V11\Version119',
            'App\Listeners\Update\V12\Version120',
            'App\Listeners\Update\V12\Version126',
            'App\Listeners\Update\V12\Version127',
            'App\Listeners\Update\V12\Version129',
            'App\Listeners\Update\V12\Version1210',
            'App\Listeners\Update\V12\Version1211',
            'App\Listeners\Update\V13\Version130',
            'App\Listeners\Update\V13\Version132',
            'App\Listeners\Update\V13\Version135',
            'App\Listeners\Update\V13\Version138',
            'App\Listeners\Update\V13\Version139',
            'App\Listeners\Update\V13\Version1311',
            'App\Listeners\Update\V13\Version1313',
            'App\Listeners\Update\V13\Version1316',
            'App\Listeners\Update\V20\Version200',
            'App\Listeners\Update\V20\Version201',
        ],
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\Auth\Login',
        ],
        'Illuminate\Auth\Events\Logout' => [
            'App\Listeners\Auth\Logout',
        ],
        'App\Events\Expense\BillCreated' => [
            'App\Listeners\Expense\CreateBillCreatedHistory',
        ],
        'App\Events\Expense\BillRecurring' => [
            'App\Listeners\Expense\SendBillRecurringNotification',
        ],
        'App\Events\Income\PaymentReceived' => [
            'App\Listeners\Income\CreateInvoiceTransaction',
            'App\Listeners\Income\SendInvoicePaymentNotification',
        ],
        'App\Events\Income\InvoiceCreated' => [
            'App\Listeners\Income\CreateInvoiceCreatedHistory',
            'App\Listeners\Income\IncreaseNextInvoiceNumber',
        ],
        'App\Events\Income\InvoiceSent' => [
            'App\Listeners\Income\MarkInvoiceSent',
        ],
        'App\Events\Income\InvoiceViewed' => [
            'App\Listeners\Income\MarkInvoiceViewed',
        ],
        'App\Events\Income\InvoiceRecurring' => [
            'App\Listeners\Income\SendInvoiceRecurringNotification',
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        'App\Listeners\Common\IncomeSummaryReport',
        'App\Listeners\Common\ExpenseSummaryReport',
        'App\Listeners\Common\IncomeExpenseSummaryReport',
        'App\Listeners\Common\TaxSummaryReport',
        'App\Listeners\Common\ProfitLossReport',
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
