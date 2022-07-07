<?php

namespace App\Listeners\Update\V30;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Jobs\Common\CreateWidget;
use App\Jobs\Setting\CreateEmailTemplate;
use App\Jobs\Setting\UpdateEmailTemplate;
use App\Models\Auth\User;
use App\Models\Common\Company;
use App\Models\Common\Dashboard;
use App\Models\Document\Document;
use App\Models\Setting\EmailTemplate;
use App\Models\Banking\Transaction;
use App\Models\Common\Recurring;
use App\Traits\Jobs;
use App\Traits\Permissions;
use App\Traits\Transactions;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Throwable;

class Version300 extends Listener
{
    use Jobs, Permissions, Transactions;

    const ALIAS = 'core';

    const VERSION = '3.0.0';

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        Log::channel('stdout')->info('Starting the Akaunting 3.0 update...');

        $this->updateDatabase();

        $this->deleteOldWidgets();

        $this->updateCompanies();

        $this->updatePermissions();

        $this->deleteOldFiles();

        $this->clearNotifications();

        Log::channel('stdout')->info('Akaunting 3.0 update finished.');
    }

    public function updateDatabase()
    {
        Log::channel('stdout')->info('Updating database...');

        DB::table('migrations')->insert([
            'id' => DB::table('migrations')->max('id') + 1,
            'migration' => '2019_11_16_000000_core_v2',
            'batch' => DB::table('migrations')->max('batch') + 1,
        ]);

        Artisan::call('migrate', ['--force' => true]);

        Log::channel('stdout')->info('Database updated.');
    }

    public function updateCompanies()
    {
        Log::channel('stdout')->info('Updating companies...');

        $company_id = company_id();

        $companies = Company::cursor();

        foreach ($companies as $company) {
            Log::channel('stdout')->info('Updating company:' . $company->id);

            $company->makeCurrent();

            $this->createNewWidgets();

            $this->updateEmailTemplates();

            $this->updateRecurables();

            $this->updateTransactions();

            Log::channel('stdout')->info('Company updated:' . $company->id);
        }

        company($company_id)->makeCurrent();

        Log::channel('stdout')->info('Companies updated.');
    }

    public function deleteOldWidgets()
    {
        Log::channel('stdout')->info('Deleting old widgets...');

        // Delete old widgets
        $old_widgets = [
            'App\\Widgets\\TotalIncome',
            'App\\Widgets\\TotalExpenses',
            'App\\Widgets\\TotalProfit',
            'App\\Widgets\\CashFlow',
            'App\\Widgets\\IncomeByCategory',
            'App\\Widgets\\ExpensesByCategory',
            'App\\Widgets\\AccountBalance',
            'App\\Widgets\\LatestIncome',
            'App\\Widgets\\LatestExpenses',
        ];

        DB::transaction(function () use ($old_widgets) {
            DB::table('widgets')->whereIn('class', $old_widgets)->delete();
        });

        Log::channel('stdout')->info('Old widgets deleted.');
    }

    public function createNewWidgets()
    {
        Log::channel('stdout')->info('Creating new widgets...');

        // Create new widgets
        $new_widgets = [
            'App\Widgets\Receivables',
            'App\Widgets\Payables',
            'App\Widgets\CashFlow',
            'App\Widgets\ProfitLoss',
            'App\Widgets\ExpensesByCategory',
            'App\Widgets\AccountBalance',
            'App\Widgets\BankFeeds',
        ];

        Log::channel('stdout')->info('Creating new widgets...');

        Dashboard::whereDoesntHave('widgets')->each(function($dashboard) use ($new_widgets) {
            $sort = 1;

            foreach ($new_widgets as $class_name) {
                $class = new $class_name();

                $this->dispatch(new CreateWidget([
                    'company_id'    => $dashboard->company_id,
                    'dashboard_id'  => $dashboard->id,
                    'class'         => $class_name,
                    'name'          => $class->getDefaultName(),
                    'sort'          => $sort,
                    'settings'      => $class->getDefaultSettings(),
                ]));

                $sort++;
            }
        });

        Log::channel('stdout')->info('New widgets created.');
    }

    public function updateEmailTemplates()
    {
        Log::channel('stdout')->info('Updating/Creating email templates...');

        $payment_received_model = EmailTemplate::alias('revenue_new_customer')->first();

        $payment_received_request = [
            'company_id'    => company_id(),
            'alias'         => 'payment_received_customer',
            'class'         => 'App\Notifications\Banking\Transaction',
            'name'          => 'settings.email.templates.payment_received_customer',
        ];

        Log::channel('stdout')->info('Updating old email templates...');

        if (!empty($payment_received_model)) {
            $this->dispatch(new UpdateEmailTemplate($payment_received_model, array_merge($payment_received_request, [
                'subject'   => $payment_received_model->subject,
                'body'      => $payment_received_model->body,
            ])));
        } else {
            $this->dispatch(new CreateEmailTemplate(array_merge($payment_received_request, [
                'subject'       => trans('email_templates.payment_received_customer.subject'),
                'body'          => trans('email_templates.payment_received_customer.body'),
                'created_from'  => 'core::seed',
            ])));
        }

        Log::channel('stdout')->info('Creating new email templates...');

        $this->dispatch(new CreateEmailTemplate([
            'company_id'    => company_id(),
            'alias'         => 'invoice_view_admin',
            'class'         => 'App\Notifications\Sale\Invoice',
            'name'          => 'settings.email.templates.invoice_view_admin',
            'subject'       => trans('email_templates.invoice_view_admin.subject'),
            'body'          => trans('email_templates.invoice_view_admin.body'),
            'created_from'  => 'core::seed',
        ]));

        $this->dispatch(new CreateEmailTemplate([
            'company_id'    => company_id(),
            'alias'         => 'payment_made_vendor',
            'class'         => 'App\Notifications\Banking\Transaction',
            'name'          => 'settings.email.templates.payment_made_vendor',
            'subject'       => trans('email_templates.payment_made_vendor.subject'),
            'body'          => trans('email_templates.payment_made_vendor.body'),
            'created_from'  => 'core::seed',
        ]));

        Log::channel('stdout')->info('Email templates updated/created.');
    }

    public function updateRecurables()
    {
        Log::channel('stdout')->info('Updating recurring...');

        $recurrings = Recurring::with('recurable')->cursor();

        foreach ($recurrings as $recurring) {
            // Document or Transaction
            $model = $recurring->recurable;

            if ($model instanceof Document) {
                $cloneable_relations = ['items', 'totals'];
                $number_field = 'document_number';
            } else {
                $cloneable_relations = [];
                $number_field = 'number';
            }

            $model->cloneable_relations = $cloneable_relations;

            // Create the recurring template
            $clone = $model->duplicate();
            $clone->type = $clone->type . '-recurring';
            $clone->$number_field = $this->getNextTransactionNumber('-recurring');
            $clone->saveQuietly();

            $this->increaseNextTransactionNumber('-recurring');

            // Update the recurring table
            $recurring->recurable_id = $clone->id;
            $recurring->saveQuietly();

            // Set the new recurring template as parent for the original model
            $model->parent_id = $clone->id;
            $model->saveQuietly();

            // Set the new recurring template as parent for child models
            DB::table($model->getTable())->where('parent_id', $model->id)->update([
                'parent_id' => $clone->id,
                'created_from' => 'core::recurring',
            ]);
        }

        Log::channel('stdout')->info('Recurring updated.');
    }

    public function updateTransactions()
    {
        Log::channel('stdout')->info('Updating transactions...');

        $transactions = Transaction::isNotRecurring()->cursor();

        $number = 1;
        $transaction_number = $this->getTransactionNumber($number);

        foreach ($transactions as $transaction) {
            $transaction->number = $transaction_number;
            $transaction->saveQuietly();

            $number++;
            $transaction_number = $this->getTransactionNumber($number);
        }

        $this->saveNextTransactionNumber($number);

        Log::channel('stdout')->info('Transactions updated.');
    }

    public function clearNotifications()
    {
        try {
            $users = User::all();

            foreach ($users as $user) {
               $notifications = $user->unreadNotifications;

                foreach ($notifications as $notification) {
                    $notification->markAsRead();
                }
            }
        } catch (\Exception $e) {}
    }

    public function getTransactionNumber($number): string
    {
        $prefix = setting('transaction.number_prefix');
        $digit  = setting('transaction.number_digit');

        return $prefix . str_pad($number, $digit, '0', STR_PAD_LEFT);
    }

    public function saveNextTransactionNumber($next): void
    {
        setting(['transaction.number_next' => $next]);
        setting()->save();
    }

    public function updatePermissions()
    {
        Log::channel('stdout')->info('Updating permissions...');

        $rows = [
            'accountant' => [
                'admin-panel' => 'r',
                'api' => 'r',
                'common-dashboards' => 'r',
                'common-items' => 'r',
                'purchases-bills' => 'r',
                'purchases-vendors' => 'r',
                'sales-customers' => 'r',
                'sales-invoices' => 'r',
                'banking-accounts' => 'r',
                'banking-reconciliations' => 'r',
                'banking-transactions' => 'r',
                'banking-transfers' => 'r',
                'reports-expense-summary' => 'r',
                'reports-income-summary' => 'r',
                'reports-income-expense-summary' => 'r',
                'reports-profit-loss' => 'r',
                'reports-tax-summary' => 'r',
                'modules-home' => 'r',
                'modules-item' => 'r',
                'modules-my' => 'r',
                'modules-tiles' => 'r',
            ],
        ];

        Log::channel('stdout')->info('Attaching new permissions...');

        // c=create, r=read, u=update, d=delete
        $this->attachPermissionsByRoleNames($rows);

        // c=create, r=read, u=update, d=delete
        $this->attachPermissionsToAdminRoles([
            'settings-email-templates' => 'r,u',
            'settings-company' => 'u',
            'settings-defaults' => 'u',
            'settings-email' => 'u',
            'settings-invoice' => 'u',
            'settings-localisation' => 'u',
            'settings-schedule' => 'u',
            'widgets-bank-feeds' => 'r',
            'widgets-payables' => 'r',
            'widgets-profit-loss' => 'r',
            'widgets-receivables' => 'r',
        ]);

        Log::channel('stdout')->info('Dettaching old permissions...');

        // c=create, r=read, u=update, d=delete
        $this->detachPermissionsFromAdminRoles([
            'auth-permissions' => 'c,r,u,d',
            'auth-roles' => 'c,r,u,d',
            'common-notifications' => 'c,r,u,d',
            'purchases-payments' => 'c,r,u,d',
            'sales-revenues' => 'c,r,u,d',
            'settings-settings' => 'r,u',
            'settings-settings' => 'r,u',
            'widgets-income-by-category' => 'r',
            'widgets-latest-expenses' => 'r',
            'widgets-latest-income' => 'r',
            'widgets-total-expenses' => 'r',
            'widgets-total-income' => 'r',
            'widgets-total-profit' => 'r',
        ]);

        Log::channel('stdout')->info('Permissions updated.');
    }

    public function deleteOldFiles()
    {
        Log::channel('stdout')->info('Deleting old files and folders...');

        $files = [
            'app/Abstracts/View/Components/Document.php',
            'app/Abstracts/View/Components/DocumentForm.php',
            'app/Abstracts/View/Components/DocumentIndex.php',
            'app/Abstracts/View/Components/DocumentShow.php',
            'app/Abstracts/View/Components/DocumentTemplate.php',
            'app/Abstracts/View/Components/Transaction.php',
            'app/Abstracts/View/Components/TransactionShow.php',
            'app/Abstracts/View/Components/TransactionTemplate.php',
            'app/Http/BulkActions/Auth/Permissions.php',
            'app/Http/BulkActions/Auth/Roles.php',
            'app/Http/BulkActions/Purchases/Payments.php',
            'app/Http/BulkActions/Sales/Revenues.php',
            'app/Http/Exports/Purchases/Payments.php',
            'app/Http/Exports/Sales/Revenues.php',
            'app/Http/Imports/Purchases/Payments.php',
            'app/Http/Imports/Sales/Revenues.php',
            'app/Http/Controllers/Api/Auth/Permissions.php',
            'app/Http/Controllers/Api/Auth/Roles.php',
            'app/Http/Controllers/Auth/Permissions.php',
            'app/Http/Controllers/Auth/Roles.php',
            'app/Http/Controllers/Common/Notifications.php',
            'app/Http/Controllers/Purchases/Payments.php',
            'app/Http/Controllers/Sales/Revenues.php',
            'app/Http/Controllers/Settings/Settings.php',
            'app/Http/Requests/Auth/Permission.php',
            'app/Http/Requests/Auth/Role.php',
            'app/Http/ViewComposers/Header.php',
            'app/Http/ViewComposers/Index.php',
            'app/Http/ViewComposers/Notifications.php',
            'app/Http/ViewComposers/Show.php',
            'app/Http/ViewComposers/Suggestions.php',
            'app/Http/ViewComposers/Menu.php',
            'app/Http/ViewComposers/Logo.php',
            'app/Http/ViewComposers/Modules.php',
            'app/Http/ViewComposers/Wizard.php',
            'app/Models/Common/EmailTemplate.php',
            'app/Notifications/Sale/Revenue.php',
            'app/Transformers/Auth/Permission.php',
            'app/Transformers/Auth/Role.php',
            'app/View/Components/Documents/Index/CardBody.php',
            'app/View/Components/Documents/Index/CardFooter.php',
            'app/View/Components/Documents/Index/CardHeader.php',
            'app/View/Components/Documents/Index/TopButtons.php',
            'app/View/Components/Documents/Show/Timeline.php',
            'app/View/Components/Documents/Show/Transactions.php',
            'app/View/Components/Transactions/Show/Header.php',
            'app/View/Components/Transactions/Show/Footer.php',
            'app/View/Components/Transfers/Show/Header.php',
            'app/View/Components/Transfers/Show/Footer.php',
            'app/Widgets/IncomeByCategory.php',
            'app/Widgets/LatestExpenses.php',
            'app/Widgets/LatestIncome.php',
            'app/Widgets/TotalExpense.php',
            'app/Widgets/TotalIncome.php',
            'app/Widgets/TotalProfit.php',
            'database/factories/Permission.php',
            'database/factories/Role.php',
            'database/migrations/2020_01_08_000000_core_v200.php',
            'database/migrations/2020_03_20_183732_core_v208.php',
            'database/migrations/2020_06_09_000000_core_v2014.php',
            'database/migrations/2020_07_20_000000_core_v2017.php',
            'database/migrations/2020_10_13_000000_core_v210.php',
            'database/migrations/2021_04_01_000000_core_v219.php',
            'database/migrations/2021_05_17_000000_core_v2114.php',
            'database/migrations/2021_06_17_000000_core_v2117.php',
            'database/migrations/2021_09_01_000000_core_v2124.php',
            'database/migrations/2021_09_10_000000_core_v2125.php',
            'database/migrations/2021_09_10_000000_core_v2126.php',
            'database/migrations/2021_09_10_000000_core_v2127.php',
            'database/migrations/2022_03_02_000000_core_v2133.php',
            'database/migrations/2022_03_23_000000_core_v2134.php',
            'public/files/import/payments.xlsx',
            'public/files/import/revenues.xlsx',
            'resources/assets/js/views/auth/permissions.js',
            'resources/assets/js/views/auth/roles.js',
            'resources/views/components/documents/index/card-body.blade.php',
            'resources/views/components/documents/index/card-footer.blade.php',
            'resources/views/components/documents/index/card-header.blade.php',
            'resources/views/components/documents/index/top-buttons.blade.php',
            'resources/views/components/documents/show/timeline.blade.php',
            'resources/views/components/documents/show/transactions.blade.php',
            'resources/views/components/form-group-title.blade.php',
            'resources/views/components/transactions/show/header.blade.php',
            'resources/views/components/transactions/show/footer.blade.php',
            'resources/views/components/transfers/show/header.blade.php',
            'resources/views/components/transfers/show/footer.blade.php',
            'resources/views/components/layouts/modules.php',
            'resources/views/layouts/admin.blade.php',
            'resources/views/layouts/auth.blade.php',
            'resources/views/layouts/install.blade.php',
            'resources/views/layouts/maintenance.blade.php',
            'resources/views/layouts/modules.blade.php',
            'resources/views/layouts/portal.blade.php',
            'resources/views/layouts/print.blade.php',
            'resources/views/layouts/signed.blade.php',
            'resources/views/layouts/wizard.blade.php',
            'resources/views/partials/admin/content.blade.php',
            'resources/views/partials/admin/empty_page.blade.php',
            'resources/views/partials/admin/favorites.blade.php',
            'resources/views/partials/admin/footer.blade.php',
            'resources/views/partials/admin/head.blade.php',
            'resources/views/partials/admin/header.blade.php',
            'resources/views/partials/admin/menu.blade.php',
            'resources/views/partials/admin/pagination.blade.php',
            'resources/views/partials/admin/scripts.blade.php',
            'resources/views/partials/admin/suggestions.blade.php',
            'resources/views/partials/auth/head.blade.php',
            'resources/views/partials/auth/scripts.blade.php',
            'resources/views/partials/form/bulk_action_all_group.blade.php',
            'resources/views/partials/form/bulk_action_group.blade.php',
            'resources/views/partials/form/bulk_action_row_group.blade.php',
            'resources/views/partials/form/checkbox_group.blade.php',
            'resources/views/partials/form/date_group.blade.php',
            'resources/views/partials/form/date_range.blade.php',
            'resources/views/partials/form/date_time_group.blade.php',
            'resources/views/partials/form/delete_button.blade.php',
            'resources/views/partials/form/delete_link.blade.php',
            'resources/views/partials/form/email_group.blade.php',
            'resources/views/partials/form/enabled_group.blade.php',
            'resources/views/partials/form/file_group.blade.php',
            'resources/views/partials/form/invoice_text.blade.php',
            'resources/views/partials/form/money_group.blade.php',
            'resources/views/partials/form/multi_select_add_new_group.blade.php',
            'resources/views/partials/form/multi_select_group.blade.php',
            'resources/views/partials/form/multi_select_remote_add_new_group.blade.php',
            'resources/views/partials/form/multi_select_remote_group.blade.php',
            'resources/views/partials/form/number_group.blade.php',
            'resources/views/partials/form/password_group.blade.php',
            'resources/views/partials/form/radio_group.blade.php',
            'resources/views/partials/form/recurring.blade.php',
            'resources/views/partials/form/save_buttons.blade.php',
            'resources/views/partials/form/select_add_new_group.blade.php',
            'resources/views/partials/form/select_group.blade.php',
            'resources/views/partials/form/select_group_add_new_group.blade.php',
            'resources/views/partials/form/select_group_group.blade.php',
            'resources/views/partials/form/select_remote_add_new_group.blade.php',
            'resources/views/partials/form/select_remote_group.blade.php',
            'resources/views/partials/form/text_editor_group.blade.php',
            'resources/views/partials/form/text_group.blade.php',
            'resources/views/partials/form/textarea_group.blade.php',
            'resources/views/partials/form/time_group.blade.php',
            'resources/views/partials/email/body.blade.php',
            'resources/views/partials/email/footer.blade.php',
            'resources/views/partials/media/file.blade.php',
            'resources/views/partials/reports/detail.blade.php',
            'resources/views/partials/reports/detail/content/footer.blade.php',
            'resources/views/partials/reports/detail/content/header.blade.php',
            'resources/views/partials/reports/detail/table.blade.php',
            'resources/views/partials/reports/detail/table/body.blade.php',
            'resources/views/partials/reports/detail/table/footer.blade.php',
            'resources/views/partials/reports/detail/table/header.blade.php',
            'resources/views/partials/reports/detail/table/row.blade.php',
            'resources/views/partials/reports/fields.blade.php',
            'resources/views/partials/reports/filter.blade.php',
            'resources/views/partials/reports/header.blade.php',
            'resources/views/partials/reports/print.blade.php',
            'resources/views/partials/reports/show.blade.php',
            'resources/views/partials/reports/summary.blade.php',
            'resources/views/partials/reports/summary/chart.blade.php',
            'resources/views/partials/reports/summary/content/footer.blade.php',
            'resources/views/partials/reports/summary/content/header.blade.php',
            'resources/views/partials/reports/summary/table.blade.php',
            'resources/views/partials/reports/summary/table/body.blade.php',
            'resources/views/partials/reports/summary/table/footer.blade.php',
            'resources/views/partials/reports/summary/table/header.blade.php',
            'resources/views/partials/reports/summary/table/row.blade.php',
            'resources/views/partials/install/head.blade.php',
            'resources/views/partials/install/scripts.blade.php',
            'resources/views/partials/maintenance/body.blade.php',
            'resources/views/partials/maintenance/head.blade.php',
            'resources/views/partials/modules/bar.blade.php',
            'resources/views/partials/modules/head.blade.php',
            'resources/views/partials/modules/item.blade.php',
            'resources/views/partials/modules/items.blade.php',
            'resources/views/partials/modules/my_apps_item.blade.php',
            'resources/views/partials/modules/no_apps.blade.php',
            'resources/views/partials/modules/pre_sale.blade.php',
            'resources/views/partials/modules/releases.blade.php',
            'resources/views/partials/modules/reviews.blade.php',
            'resources/views/partials/modules/show/price.blade.php',
            'resources/views/partials/modules/show/toggle.blade.php',
            'resources/views/partials/portal/content.blade.php',
            'resources/views/partials/portal/footer.blade.php',
            'resources/views/partials/portal/head.blade.php',
            'resources/views/partials/portal/header.blade.php',
            'resources/views/partials/portal/menu.blade.php',
            'resources/views/partials/portal/navbar.blade.php',
            'resources/views/partials/portal/pagination.blade.php',
            'resources/views/partials/portal/payment_method/hosted.blade.php',
            'resources/views/partials/portal/payment_method/redirect.blade.php',
            'resources/views/partials/portal/scripts.blade.php',
            'resources/views/partials/print/head.blade.php',
            'resources/views/partials/print/scripts.blade.php',
            'resources/views/partials/pwa/pwa.blade.php',
            'resources/views/partials/signed/content.blade.php',
            'resources/views/partials/signed/footer.blade.php',
            'resources/views/partials/signed/head.blade.php',
            'resources/views/partials/wizard/head.blade.php',
            'resources/views/partials/wizard/scripts.blade.php',
            'resources/views/partials/wizard/steps.blade.php',
            'resources/views/partials/widgets/header.blade.php',
            'resources/views/settings/settings/index.blade.php',
            'resources/views/widgets/latest_expenses.blade.php',
            'resources/views/widgets/latest_income.blade.php',
            'resources/views/widgets/total_expenses.blade.php',
            'resources/views/widgets/total_income.blade.php',
            'resources/views/widgets/total_profit.blade.php',
        ];

        $directories = [
            'app/Listeners/Update/V20',
            'app/Listeners/Update/V21',
            'modules/BC21',
            'modules/Bc21',
            'resources/views/auth/permissions',
            'resources/views/auth/roles',
            'resources/views/common/notifications',
            'resources/views/purchases/payments',
            'resources/views/sales/revenues',
            'resources/views/layouts',
            'resources/views/partials/admin',
            'resources/views/partials/auth',
            'resources/views/partials/form',
            'resources/views/partials/install',
            'resources/views/partials/maintenance',
            'resources/views/partials/modules/show',
            'resources/views/partials/modules',
            'resources/views/partials/portal/payment_method',
            'resources/views/partials/portal',
            'resources/views/partials/print',
            'resources/views/partials/pwa',
            'resources/views/partials/signed',
            'resources/views/partials/wizard',
            'resources/views/partials/email',
            'resources/views/partials/media',
            'resources/views/partials/reports/details/content',
            'resources/views/partials/reports/details/table',
            'resources/views/partials/reports/details',
            'resources/views/partials/reports/summary/content',
            'resources/views/partials/reports/summary/table',
            'resources/views/partials/reports/summary',
            'resources/views/partials/reports',
            'resources/views/partials/widgets',
        ];

        Log::channel('stdout')->info('Deleting old files...');

        foreach ($files as $file) {
            File::delete(base_path($file));
        }

        Log::channel('stdout')->info('Deleting old folders...');

        foreach ($directories as $directory) {
            File::deleteDirectory(base_path($directory));
        }

        Log::channel('stdout')->info('Old files and folders deleted.');
    }
}
