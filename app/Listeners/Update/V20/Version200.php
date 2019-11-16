<?php

namespace App\Listeners\Update\V20;

use App\Events\Install\UpdateFinished as Event;
use App\Listeners\Update\Listener;
use App\Models\Auth\User;
use App\Models\Auth\Role;
use App\Models\Auth\Permission;
use App\Models\Banking\Transaction;
use App\Models\Common\Company;
use App\Models\Common\Contact;
use App\Models\Common\EmailTemplate;
use App\Models\Common\Report;
use App\Utilities\Overrider;
use Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class Version200 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '2.0.0';

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        // Check if should listen
        if (!$this->check($event)) {
            return;
        }

        // Cache Clear
        Artisan::call('cache:clear');

        // Update database
        Artisan::call('migrate', ['--force' => true]);

        $this->updateCompanies();

        $this->createDashboards();

        $this->copyTransactions();

        $this->copyContacts();

        $this->updatePermissions();

        $this->deleteOldFiles();
    }

    protected function updateCompanies()
    {
        $company_id = session('company_id');

        $companies = Company::enabled()->cursor();

        foreach ($companies as $company) {
            $this->updateSettings($company);

            $this->createEmailTemplates($company);

            $this->createReports($company);
        }

        setting()->forgetAll();

        session(['company_id' => $company_id]);

        Overrider::load('settings');
    }

    public function updateSettings($company)
    {
        // Clear current settings
        setting()->forgetAll();

        session(['company_id' => $company->id]);

        Overrider::load('settings');

        $updated_settings = [
            'company.name'                      => 'general.company_name',
            'company.email'                     => 'general.company_email',
            'company.address'                   => 'general.company_address',
            'company.logo'                      => 'general.company_logo',
            'localisation.financial_start'      => 'general.financial_start',
            'localisation.timezone'             => 'general.timezone',
            'localisation.date_format'          => 'general.date_format',
            'localisation.date_separator'       => 'general.date_separator',
            'localisation.percent_position'     => 'general.percent_position',
            'invoice.number_prefix'             => 'general.invoice_number_prefix',
            'invoice.number_digit'              => 'general.invoice_number_digit',
            'invoice.number_next'               => 'general.invoice_number_next',
            'invoice.item_name'                 => 'general.invoice_item',
            'invoice.price_name'                => 'general.invoice_price',
            'invoice.quantity_name'             => 'general.invoice_quantity',
            'invoice.title'                     => trans_choice('general.invoices', 1),
            'invoice.payment_terms'             => '0',
            'default.account'                   => 'general.default_account',
            'default.currency'                  => 'general.default_currency',
            'default.locale'                    => 'general.default_locale',
            'default.list_limit'                => 'general.list_limit',
            'default.payment_method'            => 'general.default_payment_method',
            'default.use_gravatar'              => 'general.use_gravatar',
            'email.protocol'                    => 'general.email_protocol',
            'email.sendmail_path'               => 'general.email_sendmail_path',
            'email.smtp_host'                   => 'general.smtp_host',
            'email.smtp_port'                   => 'general.smtp_port',
            'email.smtp_username'               => 'general.smtp_username',
            'email.smtp_password'               => 'general.smtp_password',
            'email.smtp_encryption'             => 'general.smtp_encryption',
            'schedule.send_invoice_reminder'    => 'general.send_invoice_reminder',
            'schedule.invoice_days'             => 'general.schedule_invoice_days',
            'schedule.send_bill_reminder'       => 'general.send_bill_reminder',
            'schedule.bill_days'                => 'general.schedule_bill_days',
            'schedule.time'                     => 'general.schedule_time',
            'apps.api_key'                      => 'general.api_token',
            'wizard.completed'                  => 'general.wizard',
            'contact.type.customer'             => 'customer',
            'contact.type.vendor'               => 'vendor',
            'offline-payments.methods'          => 'offlinepayment.methods',
        ];

        foreach ($updated_settings as $new => $old) {
            switch($new) {
                case 'offline-payments.methods':
                case 'default.payment_method':
                    $value = str_replace('offlinepayment.', 'offline-payments.', setting($old));

                    break;
                case 'invoice.title':
                case 'invoice.payment_terms':
                case 'contact.type.customer':
                case 'contact.type.vendor':
                    $value = $old;

                    break;
                default:
                    $value = setting($old);

                    break;
            }

            if (($value != '0') && empty($value)) {
                continue;
            }

            setting()->set([$new => $value]);
            setting()->forget($old);
        }

        $removed_settings = [
            'general.admin_theme',
            'general.session_handler',
            'general.session_lifetime',
            'general.file_size',
            'general.file_types',
            'general.send_item_reminder',
            'general.schedule_item_stocks',
        ];

        foreach ($removed_settings as $removed_setting) {
            setting()->forget($removed_setting);
        }

        setting()->save();
    }

    public function createEmailTemplates($company)
    {
        $templates = [
            'invoice_new_customer',
            'invoice_remind_customer',
            'invoice_remind_admin',
            'invoice_recur_customer',
            'invoice_recur_admin',
            'invoice_payment_customer',
            'invoice_payment_admin',
            'bill_remind_admin',
            'bill_recur_admin',
        ];

        foreach ($templates as $template) {
            EmailTemplate::create([
                'company_id' => $company->id,
                'alias' => $template,
                'subject' => trans('email_templates.' . $template . '.subject'),
                'body' => trans('email_templates.' . $template . '.body'),
            ]);
        }
    }

    public function createReports($company)
    {
        $rows = [
            [
                'company_id' => $company->id,
                'name' => trans('reports.summary.income'),
                'description' => 'This is the income summary by category.',
                'class' => 'App\Reports\IncomeSummary',
                'group' => 'category',
                'period' => 'monthly',
                'basis' => 'accrual',
                'chart' => 'line',
                'enabled' => 1,
            ],
            [
                'company_id' => $company->id,
                'name' => trans('reports.summary.expense'),
                'description' => 'This is the expense summary by category.',
                'class' => 'App\Reports\ExpenseSummary',
                'group' => 'category',
                'period' => 'monthly',
                'basis' => 'accrual',
                'chart' => 'line',
                'enabled' => 1,
            ],
            [
                'company_id' => $company->id,
                'name' => trans('reports.summary.income_expense'),
                'description' => 'This is the income vs expense by category.',
                'class' => 'App\Reports\IncomeExpenseSummary',
                'group' => 'category',
                'period' => 'monthly',
                'basis' => 'accrual',
                'chart' => 'line',
                'enabled' => 1,
            ],
            [
                'company_id' => $company->id,
                'name' => trans('reports.summary.tax'),
                'description' => 'This is the tax summary by category.',
                'class' => 'App\Reports\TaxSummary',
                'group' => 'category',
                'period' => 'quarterly',
                'basis' => 'accrual',
                'chart' => 'line',
                'enabled' => 1,
            ],
            [
                'company_id' => $company->id,
                'name' => trans('reports.profit_loss'),
                'description' => 'This is the profit & loss by category.',
                'class' => 'App\Reports\ProfitLoss',
                'group' => 'category',
                'period' => 'quarterly',
                'basis' => 'accrual',
                'chart' => 'line',
                'enabled' => 1,
            ],
        ];

        foreach ($rows as $row) {
            Report::create($row);
        }
    }

    public function createDashboards()
    {
        $users = User::enabled()->cursor();

        foreach ($users as $user) {
            $companies = $user->companies;

            foreach ($companies as $company) {
                Artisan::call('user:seed', [
                    'user' => $user->id,
                    'company' => $company->id,
                ]);
            }
        }
    }

    public function copyTransactions()
    {
        $this->copyInvoicePayments();

        $this->copyRevenues();

        $this->copyBillPayments();

        $this->copyPayments();
    }

    public function copyInvoicePayments()
    {
        $invoice_payments = DB::table('invoice_payments')->cursor();

        foreach ($invoice_payments as $invoice_payment) {
            $invoice = DB::table('invoices')->where('id', $invoice_payment->invoice_id)->first();

            $payment_method = str_replace('offlinepayment.', 'offline-payments.', $invoice_payment->payment_method);

            $transaction = $this->create(new Transaction(), [
                'company_id' => $invoice_payment->company_id,
                'type' => 'income',
                'account_id' => $invoice_payment->account_id,
                'paid_at' => $invoice_payment->paid_at,
                'amount' => $invoice_payment->amount,
                'currency_code' => $invoice_payment->currency_code,
                'currency_rate' => $invoice_payment->currency_rate,
                'document_id' => $invoice_payment->invoice_id,
                'contact_id' => $invoice->contact_id,
                'description' => $invoice_payment->description,
                'category_id' => $invoice->category_id,
                'payment_method' => $payment_method,
                'reference' => $invoice_payment->reference,
                'parent_id' => $invoice->parent_id,
                'reconciled' => $invoice_payment->reconciled,
                'created_at' => $invoice_payment->created_at,
                'updated_at' => $invoice_payment->updated_at,
                'deleted_at' => $invoice_payment->deleted_at,
            ]);

            if (Schema::hasTable('double_entry_ledger')) {
                DB::table('double_entry_ledger')
                    ->where('ledgerable_id', $invoice_payment->id)
                    ->where('ledgerable_type', 'App\Models\Income\InvoicePayment')
                    ->update([
                        'ledgerable_id' => $transaction->id,
                        'ledgerable_type' => 'App\Models\Banking\Transaction',
                    ]);
            }
        }

        DB::table('invoice_payments')->delete();
    }

    public function copyRevenues()
    {
        $revenues = DB::table('revenues')->cursor();

        foreach ($revenues as $revenue) {
            $transaction = $this->create(new Transaction(), [
                'company_id' => $revenue->company_id,
                'type' => 'income',
                'account_id' => $revenue->account_id,
                'paid_at' => $revenue->paid_at,
                'amount' => $revenue->amount,
                'currency_code' => $revenue->currency_code,
                'currency_rate' => $revenue->currency_rate,
                'contact_id' => $revenue->customer_id,
                'description' => $revenue->description,
                'category_id' => $revenue->category_id,
                'payment_method' => $revenue->payment_method,
                'reference' => $revenue->reference,
                'parent_id' => $revenue->parent_id,
                'reconciled' => $revenue->reconciled,
                'created_at' => $revenue->created_at,
                'updated_at' => $revenue->updated_at,
                'deleted_at' => $revenue->deleted_at,
            ]);

            DB::table('transfers')
                ->where('expense_transaction_id', $revenue->id)
                ->update([
                    'expense_transaction_id' => $transaction->id,
                ]);

            DB::table('recurring')
                ->where('recurable_id', $revenue->id)
                ->where('recurable_type', 'App\Models\Income\Revenue')
                ->update([
                    'recurable_id' => $transaction->id,
                    'recurable_type' => 'App\Models\Banking\Transaction',
                ]);

            DB::table('mediables')
                ->where('mediable_id', $revenue->id)
                ->where('mediable_type', 'App\Models\Income\Revenue')
                ->update([
                    'mediable_id' => $transaction->id,
                    'mediable_type' => 'App\Models\Banking\Transaction',
                ]);

            if (Schema::hasTable('double_entry_ledger')) {
                DB::table('double_entry_ledger')
                    ->where('ledgerable_id', $revenue->id)
                    ->where('ledgerable_type', 'App\Models\Income\Revenue')
                    ->update([
                        'ledgerable_id' => $transaction->id,
                        'ledgerable_type' => 'App\Models\Banking\Transaction',
                    ]);
            }

            if (Schema::hasTable('project_revenues')) {
                DB::table('project_revenues')
                    ->where('revenue_id', $revenue->id)
                    ->update([
                        'revenue_id' => $transaction->id,
                    ]);
            }
        }

        DB::table('revenues')->delete();
    }

    public function copyBillPayments()
    {
        $bill_payments = DB::table('bill_payments')->cursor();

        foreach ($bill_payments as $bill_payment) {
            $bill = DB::table('bills')->where('id', $bill_payment->bill_id)->first();

            $payment_method = str_replace('offlinepayment.', 'offline-payments.', $bill_payment->payment_method);

            $transaction = $this->create(new Transaction(), [
                'company_id' => $bill_payment->company_id,
                'type' => 'expense',
                'account_id' => $bill_payment->account_id,
                'paid_at' => $bill_payment->paid_at,
                'amount' => $bill_payment->amount,
                'currency_code' => $bill_payment->currency_code,
                'currency_rate' => $bill_payment->currency_rate,
                'document_id' => $bill_payment->bill_id,
                'contact_id' => $bill->contact_id,
                'description' => $bill_payment->description,
                'category_id' => $bill->category_id,
                'payment_method' => $payment_method,
                'reference' => $bill_payment->reference,
                'parent_id' => $bill->parent_id,
                'reconciled' => $bill_payment->reconciled,
                'created_at' => $bill_payment->created_at,
                'updated_at' => $bill_payment->updated_at,
                'deleted_at' => $bill_payment->deleted_at,
            ]);

            if (Schema::hasTable('double_entry_ledger')) {
                DB::table('double_entry_ledger')
                    ->where('ledgerable_id', $bill_payment->id)
                    ->where('ledgerable_type', 'App\Models\Expense\BillPayment')
                    ->update([
                        'ledgerable_id' => $transaction->id,
                        'ledgerable_type' => 'App\Models\Banking\Transaction',
                    ]);
            }
        }

        DB::table('bill_payments')->delete();
    }

    public function copyPayments()
    {
        $payments = DB::table('payments')->cursor();

        foreach ($payments as $payment) {
            $transaction = $this->create(new Transaction(), [
                'company_id' => $payment->company_id,
                'type' => 'expense',
                'account_id' => $payment->account_id,
                'paid_at' => $payment->paid_at,
                'amount' => $payment->amount,
                'currency_code' => $payment->currency_code,
                'currency_rate' => $payment->currency_rate,
                'contact_id' => $payment->vendor_id,
                'description' => $payment->description,
                'category_id' => $payment->category_id,
                'payment_method' => $payment->payment_method,
                'reference' => $payment->reference,
                'parent_id' => $payment->parent_id,
                'reconciled' => $payment->reconciled,
                'created_at' => $payment->created_at,
                'updated_at' => $payment->updated_at,
                'deleted_at' => $payment->deleted_at,
            ]);

            DB::table('transfers')
                ->where('expense_transaction_id', $payment->id)
                ->update([
                    'expense_transaction_id' => $transaction->id,
                ]);

            DB::table('recurring')
                ->where('recurable_id', $payment->id)
                ->where('recurable_type', 'App\Models\Expense\Payment')
                ->update([
                    'recurable_id' => $transaction->id,
                    'recurable_type' => 'App\Models\Banking\Transaction',
                ]);

            DB::table('mediables')
                ->where('mediable_id', $payment->id)
                ->where('mediable_type', 'App\Models\Expense\Payment')
                ->update([
                    'mediable_id' => $transaction->id,
                    'mediable_type' => 'App\Models\Banking\Transaction',
                ]);

            if (Schema::hasTable('double_entry_ledger')) {
                DB::table('double_entry_ledger')
                    ->where('ledgerable_id', $payment->id)
                    ->where('ledgerable_type', 'App\Models\Expense\Payment')
                    ->update([
                        'ledgerable_id' => $transaction->id,
                        'ledgerable_type' => 'App\Models\Banking\Transaction',
                    ]);
            }

            if (Schema::hasTable('project_payments')) {
                DB::table('project_payments')
                    ->where('payment_id', $payment->id)
                    ->update([
                        'payment_id' => $transaction->id,
                    ]);
            }

            if (Schema::hasTable('receipts')) {
                DB::table('receipts')
                    ->where('payment_id', $payment->id)
                    ->update([
                        'payment_id' => $transaction->id,
                    ]);
            }
        }

        DB::table('payments')->delete();
    }

    public function copyContacts()
    {
        $this->copyCustomers();

        $this->copyVendors();
    }

    public function copyCustomers()
    {
        $customers = DB::table('customers')->cursor();

        foreach ($customers as $customer) {
            $data = (array) $customer;
            $data['type'] = 'customer';

            $contact = $this->create(new Contact(), $data);

            DB::table('invoices')
                ->where('contact_id', $customer->id)
                ->update([
                    'contact_id' => $contact->id,
                ]);

            DB::table('transactions')
                ->where('contact_id', $customer->id)
                ->update([
                    'contact_id' => $contact->id,
                ]);

            if (Schema::hasTable('estimates')) {
                DB::table('estimates')
                    ->where('customer_id', $customer->id)
                    ->update([
                        'customer_id' => $contact->id,
                    ]);
            }

            if (Schema::hasTable('crm_companies')) {
                DB::table('crm_companies')
                    ->where('core_customer_id', $customer->id)
                    ->update([
                        'core_customer_id' => $contact->id,
                    ]);
            }

            if (Schema::hasTable('crm_contacts')) {
                DB::table('crm_contacts')
                    ->where('core_customer_id', $customer->id)
                    ->update([
                        'core_customer_id' => $contact->id,
                    ]);
            }

            if (Schema::hasTable('idea_soft_histories')) {
                DB::table('idea_soft_histories')
                    ->where('model_id', $customer->id)
                    ->where('model_type', 'App\Models\Income\Customer')
                    ->update([
                        'model_id' => $contact->id,
                        'model_type' => 'App\Models\Common\Contact',
                    ]);
            }
        }

        DB::table('customers')->delete();
    }

    public function copyVendors()
    {
        $vendors = DB::table('vendors')->cursor();

        foreach ($vendors as $vendor) {
            $data = (array) $vendor;
            $data['type'] = 'vendor';

            $contact = $this->create(new Contact(), $data);

            DB::table('bills')
                ->where('contact_id', $vendor->id)
                ->update([
                    'contact_id' => $contact->id,
                ]);

            DB::table('transactions')
                ->where('contact_id', $vendor->id)
                ->update([
                    'contact_id' => $contact->id,
                ]);
        }

        DB::table('vendors')->delete();
    }

    public function updatePermissions()
    {
        $this->attachPermissions([
            'admin' => [
                'common-reports' => 'c,r,u,d',
                'common-search' => 'r',
                'common-widgets' => 'c,r,u,d',
                'modules-api-key' => 'c,u',
                'settings-appearance' => 'r,u',
                'settings-company' => 'r',
                'settings-defaults' => 'r',
                'settings-email' => 'r',
                'settings-invoice' => 'r',
                'settings-localisation' => 'r',
                'settings-modules' => 'r,u',
                'settings-schedule' => 'r',
            ],
            'manager' => [
                'common-reports' => 'c,r,u,d',
                'common-search' => 'r',
                'common-widgets' => 'r',
                'settings-company' => 'r',
                'settings-defaults' => 'r',
                'settings-email' => 'r',
                'settings-invoice' => 'r',
                'settings-localisation' => 'r',
                'settings-modules' => 'r,u',
                'settings-schedule' => 'r',
            ],
            'customer' => [
                'client-portal' => 'r',
                'portal-invoices' => 'r,u',
                'portal-payments' => 'r,u',
                'portal-transactions' => 'r',
                'portal-profile' => 'r,u',
            ],
        ]);

        $this->detachPermissions([
            'admin' => [
                'read-modules-token',
                'update-modules-token',
            ],
            'customer' => [
                'read-customer-panel',
                'read-customers-invoices',
                'update-customers-invoices',
                'read-customers-payments',
                'update-customers-payments',
                'read-customers-transactions',
                'read-customers-profile',
                'update-customers-profile',
            ],
        ]);
    }

    public function attachPermissions($items)
    {
        $map = collect([
            'c' => 'create',
            'r' => 'read',
            'u' => 'update',
            'd' => 'delete'
        ]);

        foreach ($items as $role_name => $modules) {
            $role = Role::where('name', $role_name)->first();

            // Reading role permission modules
            foreach ($modules as $module => $value) {
                $permissions = explode(',', $value);

                foreach ($permissions as $p => $perm) {
                    $permissionValue = $map->get($perm);

                    $moduleName = ucwords(str_replace("-", " ", $module));

                    $permission = Permission::firstOrCreate([
                        'name' => $permissionValue . '-' . $module,
                        'display_name' => ucfirst($permissionValue) . ' ' . $moduleName,
                        'description' => ucfirst($permissionValue) . ' ' . $moduleName,
                    ]);

                    $this->command->info('Creating Permission to '.$permissionValue.' for '. $moduleName);

                    if (!$role->hasPermission($permission->name)) {
                        $role->attachPermission($permission);
                    } else {
                        $this->command->info($role_name . ': ' . $p . ' ' . $permissionValue . ' already exist');
                    }
                }
            }
        }
    }

    public function detachPermissions($items)
    {
        foreach ($items as $role_name => $permissions) {
            $role = Role::where('name', $role_name)->first();

            foreach ($permissions as $permission) {
                $role->detachPermission($permission);
                $permission->delete();
            }
        }
    }

    public function deleteOldFiles()
    {
        $files = [
            'app/Console/Commands/ModuleDelete.php',
            'app/Console/Commands/ModuleDisable.php',
            'app/Console/Commands/ModuleEnable.php',
            'app/Console/Commands/ModuleInstall.php',
            'app/Console/Stubs/modules/route-provider.stub',
            'app/Console/Stubs/modules/routes.stub',
            'app/Console/Stubs/modules/start.stub',
            'app/Console/Stubs/modules/views/master.stub',
            'app/Events/AdminMenuCreated.php',
            'app/Events/BillCreated.php',
            'app/Events/BillUpdated.php',
            'app/Events/CompanySwitched.php',
            'app/Events/CustomerMenuCreated.php',
            'app/Events/InvoiceCreated.php',
            'app/Events/InvoicePaid.php',
            'app/Events/InvoicePrinting.php',
            'app/Events/InvoiceUpdated.php',
            'app/Events/ModuleInstalled.php',
            'app/Events/PaymentGatewayListing.php',
            'app/Events/UpdateFinished.php',
            'app/Http/Controllers/Api/Expenses/Payments.php',
            'app/Http/Controllers/Api/Expenses/Vendors.php',
            'app/Http/Controllers/Api/Incomes/Customers.php',
            'app/Http/Controllers/Api/Incomes/InvoicePayments.php',
            'app/Http/Controllers/Api/Incomes/Revenues.php',
            'app/Http/Controllers/ApiController.php',
            'app/Http/Controllers/Controller.php',
            'app/Http/Controllers/Modals/BillPayments.php',
            'app/Http/Controllers/Modals/InvoicePayments.php',
            'app/Http/Controllers/modules/Token.php',
            'app/Http/Middleware/CustomerMenu.php',
            'app/Http/Middleware/SignedUrlCompany.php',
            'app/Http/Requests/Expense/BillPayment.php',
            'app/Http/Requests/Expense/Payment.php',
            'app/Http/Requests/Expense/Vendor.php',
            'app/Http/Requests/Income/Customer.php',
            'app/Http/Requests/Income/InvoicePayment.php',
            'app/Http/Requests/Income/Revenue.php',
            'app/Http/Requests/Request.php',
            'app/Http/ViewComposers/All.php',
            'app/Jobs/Expense/CreateBillPayment.php',
            'app/Jobs/Income/CreateInvoicePayment.php',
            'app/Models/Company/Company.php',
            'app/Models/Expense/BillPayment.php',
            'app/Models/Expense/Payment.php',
            'app/Models/Expense/Vendor.php',
            'app/Models/Income/Customer.php',
            'app/Models/Income/InvoicePayment.php',
            'app/Models/Income/Revenue.php',
            'app/Models/Item/Item.php',
            'app/Models/Model.php',
            'app/Notifications/Common/Item.php',
            'app/Notifications/Common/ItemReminder.php',
            'app/Notifications/Customer/Invoice.php',
            'app/Providers/AppServiceProvider.php',
            'app/Providers/AuthServiceProvider.php',
            'app/Providers/BroadcastServiceProvider.php',
            'app/Providers/EventServiceProvider.php',
            'app/Providers/FormServiceProvider.php',
            'app/Providers/ObserverServiceProvider.php',
            'app/Providers/RouteServiceProvider.php',
            'app/Providers/ValidationServiceProvider.php',
            'app/Providers/ViewComposerServiceProvider.php',
            'app/Transformers/Company/Company.php',
            'app/Transformers/Expense/BillPayments.php',
            'app/Transformers/Expense/Payment.php',
            'app/Transformers/Expense/Vendor.php',
            'app/Transformers/Income/Customer.php',
            'app/Transformers/Income/InvoicePayments.php',
            'app/Transformers/Income/Revenue.php',
            'app/Transformers/Item/Item.php',
            'config/dotenv-editor.php',
            'config/eloquentfilter.php',
            'config/menus.php',
            'config/modules.php',
            'docker-compose.yml',
            'Dockerfile',
            'modules/PaypalStandard/Http/Controllers/PaypalStandard.php',
            'modules/PaypalStandard/Http/routes.php',
            'modules/PaypalStandard/Listeners/Gateway.php',
            'modules/PaypalStandard/Providers/PaypalStandardServiceProvider.php',
            'modules/PaypalStandard/start.php',
            'public/css/akaunting-green.css',
            'public/css/app.css',
            'public/css/bootstrap3-print-fix.css',
            'public/css/bootstrap-fancyfile.css',
            'public/css/countdown.css',
            'public/css/daterangepicker.css',
            'public/css/ekko-lightbox.css',
            'public/css/font-awesome.min.css',
            'public/css/install.css',
            'public/css/invoice.css',
            'public/css/ionicons.min.css',
            'public/css/jquery.countdown.css',
            'public/css/modules.css',
            'public/css/skin-black.css',
            'public/fonts/FontAwesome.otf',
            'public/fonts/fontawesome-webfont.eot',
            'public/fonts/fontawesome-webfont.svg',
            'public/fonts/fontawesome-webfont.ttf',
            'public/fonts/fontawesome-webfont.woff',
            'public/fonts/fontawesome-webfont.woff2',
            'public/img/install.jpg',
            'public/img/login.jpg',
            'public/img/maintanance.png',
            'public/js/bootstrap-fancyfile.js',
            'public/js/jquery/jquery.maskMoney.js',
            'resources/assets/js/app.js',
            'resources/assets/js/components/Example.vue',
            'resources/assets/sass/_variables.scss',
            'resources/assets/sass/app.scss',
            'resources/views/layouts/customer.blade.php',
            'resources/views/layouts/link.blade.php',
            'resources/views/modules/token/create.blade.php',
            'resources/views/partials/link/content.blade.php',
            'resources/views/partials/link/footer.blade.php',
            'resources/views/partials/link/head.blade.php',
            'resources/views/reports/profit_loss/body.blade.php',
            'resources/views/reports/profit_loss/index.blade.php',
            'resources/views/reports/profit_loss/print.blade.php',
            'resources/views/reports/tax_summary/body.blade.php',
            'resources/views/reports/tax_summary/index.blade.php',
            'resources/views/reports/tax_summary/print.blade.php',
            'resources/views/settings/settings/edit.blade.php',
            'resources/views/vendor/flash/message.blade.php',
            'resources/views/vendor/flash/modal.blade.php',
            'resources/views/wizard/currencies/create.blade.php',
            'resources/views/wizard/currencies/edit.blade.php',
            'resources/views/wizard/taxes/create.blade.php',
            'resources/views/wizard/taxes/edit.blade.php',
            'routes/web.php',
        ];

        $directories = [
            'app/Filters',
            'app/Http/Controllers/Customers',
            'app/Http/Controllers/Reports',
            'app/Http/Requests/Customer',
            'app/Listeners/Incomes',
            'app/Listeners/Updates',
            'app/Overrides',
            'modules/OfflinePayment',
            'public/js/chartjs',
            'public/js/daterangepicker',
            'public/js/highchart',
            'public/js/lightbox',
            'public/js/moment',
            'resources/views/customers',
            'resources/views/partials/customer',
            'resources/views/reports/expense_summary',
            'resources/views/reports/income_expense_summary',
            'resources/views/reports/income_summary',
            'tests/Feature/Reports',
        ];

        foreach ($files as $file) {
            Storage::delete(base_path($file));
        }

        foreach ($directories as $directory) {
            Storage::deleteDirectory(base_path($directory));
        }
    }

    protected function create($model, $data)
    {
        $model->timestamps = false;

        $model->fillable(array_merge($model->getFillable(), ['created_at', 'updated_at', 'deleted_at']));
        $model->fill($data);

        $model->save();

        return $model;
    }
}
