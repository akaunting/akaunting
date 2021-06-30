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
            'App\Listeners\Module\UpdateExtraModules',
            'App\Listeners\Update\V20\Version200',
            'App\Listeners\Update\V20\Version203',
            'App\Listeners\Update\V20\Version205',
            'App\Listeners\Update\V20\Version207',
            'App\Listeners\Update\V20\Version208',
            'App\Listeners\Update\V20\Version209',
            'App\Listeners\Update\V20\Version2014',
            'App\Listeners\Update\V20\Version2017',
            'App\Listeners\Update\V20\Version2020',
            'App\Listeners\Update\V20\Version2023',
            'App\Listeners\Update\V20\Version2024',
            'App\Listeners\Update\V21\Version210',
            'App\Listeners\Update\V21\Version213',
            'App\Listeners\Update\V21\Version218',
            'App\Listeners\Update\V21\Version219',
            'App\Listeners\Update\V21\Version2112',
            'App\Listeners\Update\V21\Version2114',
            'App\Listeners\Update\V21\Version2116',
            'App\Listeners\Update\V21\Version2117',
            'App\Listeners\Update\V21\Version2118',
        ],
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\Auth\Login',
        ],
        'Illuminate\Auth\Events\Logout' => [
            'App\Listeners\Auth\Logout',
        ],
        'App\Events\Auth\LandingPageShowing' => [
            'App\Listeners\Auth\AddLandingPages',
        ],
        'App\Events\Document\DocumentCreated' => [
            'App\Listeners\Document\CreateDocumentCreatedHistory',
            'App\Listeners\Document\IncreaseNextDocumentNumber',
            'App\Listeners\Document\SettingFieldCreated',
        ],
        'App\Events\Document\DocumentReceived' => [
            'App\Listeners\Document\MarkDocumentReceived',
        ],
        'App\Events\Document\DocumentCancelled' => [
            'App\Listeners\Document\MarkDocumentCancelled',
        ],
        'App\Events\Document\DocumentRecurring' => [
            'App\Listeners\Document\SendDocumentRecurringNotification',
        ],
        'App\Events\Document\DocumentReminded' => [
            'App\Listeners\Document\SendDocumentReminderNotification',
        ],
        'App\Events\Document\PaymentReceived' => [
            'App\Listeners\Document\CreateDocumentTransaction',
            'App\Listeners\Document\SendDocumentPaymentNotification',
        ],
        'App\Events\Document\DocumentSent' => [
            'App\Listeners\Document\MarkDocumentSent',
        ],
        'App\Events\Document\DocumentUpdated' => [
            'App\Listeners\Document\SettingFieldUpdated',
        ],
        'App\Events\Document\DocumentViewed' => [
            'App\Listeners\Document\MarkDocumentViewed',
        ],
        'App\Events\Install\UpdateFailed' => [
            'App\Listeners\Update\SendNotificationOnFailure',
        ],
        'App\Events\Menu\AdminCreated' => [
            'App\Listeners\Menu\AddAdminItems',
        ],
        'App\Events\Menu\PortalCreated' => [
            'App\Listeners\Menu\AddPortalItems',
        ],
        'App\Events\Module\Installed' => [
            'App\Listeners\Module\InstallExtraModules',
            'App\Listeners\Module\FinishInstallation',
        ],
        'App\Events\Module\Uninstalled' => [
            'App\Listeners\Module\FinishUninstallation',
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        'App\Listeners\Module\ClearCache',
        'App\Listeners\Report\AddDate',
        'App\Listeners\Report\AddAccounts',
        'App\Listeners\Report\AddCustomers',
        'App\Listeners\Report\AddVendors',
        'App\Listeners\Report\AddExpenseCategories',
        'App\Listeners\Report\AddIncomeCategories',
        'App\Listeners\Report\AddIncomeExpenseCategories',
        'App\Listeners\Report\AddSearchString',
        'App\Listeners\Report\AddRowsToTax',
    ];
}
