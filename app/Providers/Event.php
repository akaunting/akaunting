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
        \App\Events\Install\UpdateFinished::class => [
            \App\Listeners\Update\CreateModuleUpdatedHistory::class,
            \App\Listeners\Module\UpdateExtraModules::class,
            \App\Listeners\Update\V30\Version300::class,
            \App\Listeners\Update\V30\Version303::class,
            \App\Listeners\Update\V30\Version304::class,
            \App\Listeners\Update\V30\Version305::class,
            \App\Listeners\Update\V30\Version307::class,
            \App\Listeners\Update\V30\Version309::class,
            \App\Listeners\Update\V30\Version3013::class,
            \App\Listeners\Update\V30\Version3014::class,
            \App\Listeners\Update\V30\Version3015::class,
            \App\Listeners\Update\V30\Version3016::class,
            \App\Listeners\Update\V30\Version3017::class,
            \App\Listeners\Update\V31\Version310::class,
            \App\Listeners\Update\V31\Version315::class,
            \App\Listeners\Update\V31\Version317::class,
            \App\Listeners\Update\V31\Version318::class,
            \App\Listeners\Update\V31\Version3112::class,
            \App\Listeners\Update\V31\Version3115::class,
            \App\Listeners\Update\V31\Version3119::class,
        ],
        \Illuminate\Routing\Events\PreparingResponse::class => [
            \App\Listeners\Common\PreparingResponse::class,
        ],
        \Illuminate\Auth\Events\Login::class => [
            \App\Listeners\Auth\Login::class,
        ],
        \Illuminate\Auth\Events\Logout::class => [
            \App\Listeners\Auth\Logout::class,
        ],
        //'Illuminate\Console\Events\ScheduledTaskStarting' => [
        \Illuminate\Console\Events\CommandStarting::class => [
            \App\Listeners\Common\SkipScheduleInReadOnlyMode::class,
        ],
        \App\Events\Auth\LandingPageShowing::class => [
            \App\Listeners\Auth\AddLandingPages::class,
        ],
        \App\Events\Document\DocumentCreated::class => [
            \App\Listeners\Document\CreateDocumentCreatedHistory::class,
            \App\Listeners\Document\IncreaseNextDocumentNumber::class,
            \App\Listeners\Document\SettingFieldCreated::class,
        ],
        \App\Events\Document\DocumentReceived::class => [
            \App\Listeners\Document\MarkDocumentReceived::class,
        ],
        \App\Events\Document\DocumentCancelled::class => [
            \App\Listeners\Document\MarkDocumentCancelled::class,
        ],
        \App\Events\Document\DocumentRestored::class => [
            \App\Listeners\Document\RestoreDocument::class,
        ],
        \App\Events\Document\DocumentRecurring::class => [
            \App\Listeners\Document\SendDocumentRecurringNotification::class,
        ],
        \App\Events\Document\DocumentReminded::class => [
            \App\Listeners\Document\SendDocumentReminderNotification::class,
        ],
        \App\Events\Document\PaymentReceived::class => [
            \App\Listeners\Document\CreateDocumentTransaction::class,
            \App\Listeners\Document\SendDocumentPaymentNotification::class,
        ],
        \App\Events\Document\DocumentMarkedSent::class => [
            \App\Listeners\Document\MarkDocumentSent::class,
        ],
        \App\Events\Document\DocumentSent::class => [
            \App\Listeners\Document\MarkDocumentSent::class,
        ],
        \App\Events\Document\DocumentUpdated::class => [
            \App\Listeners\Document\SettingFieldUpdated::class,
        ],
        \App\Events\Document\DocumentViewed::class => [
            \App\Listeners\Document\MarkDocumentViewed::class,
            \App\Listeners\Document\SendDocumentViewNotification::class,
        ],
        \App\Events\Install\UpdateFailed::class => [
            \App\Listeners\Update\SendNotificationOnFailure::class,
        ],
        \App\Events\Menu\NotificationsCreated::class => [
            \App\Listeners\Menu\ShowInNotifications::class,
        ],
        \App\Events\Menu\AdminCreated::class => [
            \App\Listeners\Menu\ShowInAdmin::class,
        ],
        \App\Events\Menu\ProfileCreated::class => [
            \App\Listeners\Menu\ShowInProfile::class,
        ],
        \App\Events\Menu\SettingsCreated::class => [
            \App\Listeners\Menu\ShowInSettings::class,
        ],
        \App\Events\Menu\NewwCreated::class => [
            \App\Listeners\Menu\ShowInNeww::class,
        ],
        \App\Events\Menu\PortalCreated::class => [
            \App\Listeners\Menu\ShowInPortal::class,
        ],
        \App\Events\Module\Installed::class => [
            \App\Listeners\Module\InstallExtraModules::class,
            \App\Listeners\Module\FinishInstallation::class,
        ],
        \App\Events\Module\Uninstalled::class => [
            \App\Listeners\Module\FinishUninstallation::class,
        ],
        \App\Events\Banking\TransactionCreated::class => [
            \App\Listeners\Banking\IncreaseNextTransactionNumber::class,
        ],
        \App\Events\Setting\CategoryDeleted::class => [
            \App\Listeners\Setting\DeleteCategoryDeletedSubCategories::class,
        ],
        \App\Events\Email\TooManyEmailsSent::class => [
            \App\Listeners\Email\ReportTooManyEmailsSent::class,
            \App\Listeners\Email\TellFirewallTooManyEmailsSent::class,
        ],
        \App\Events\Email\InvalidEmailDetected::class => [
            \App\Listeners\Email\DisablePersonDueToInvalidEmail::class,
            \App\Listeners\Email\SendInvalidEmailNotification::class,
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        \App\Listeners\Common\ClearPlansCache::class,
        \App\Listeners\Module\ClearCache::class,
        \App\Listeners\Report\AddAccounts::class,
        \App\Listeners\Report\AddContacts::class,
        \App\Listeners\Report\AddCustomers::class,
        \App\Listeners\Report\AddVendors::class,
        \App\Listeners\Report\AddExpenseCategories::class,
        \App\Listeners\Report\AddIncomeCategories::class,
        \App\Listeners\Report\AddIncomeExpenseCategories::class,
        \App\Listeners\Report\AddSearchString::class,
        \App\Listeners\Report\AddRowsToTax::class,
        \App\Listeners\Report\AddGroup::class,
        \App\Listeners\Report\AddBasis::class,
        \App\Listeners\Report\AddPeriod::class,
        \App\Listeners\Report\AddDate::class,
        \App\Listeners\Report\AddDiscount::class,
    ];
}
