<?php

namespace App\Listeners\Update\V20;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Models\Auth\User;
use App\Models\Common\Company;
use App\Traits\Permissions;
use App\Utilities\Installer;
use App\Utilities\Overrider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class Version200 extends Listener
{
    use Permissions;

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
        if ($this->skipThisUpdate($event)) {
            return;
        }

        $this->updateDatabase();

        $this->updateCompanies();

        $this->createDashboards();

        $this->copyContacts();

        $this->copyTransactions();

        $this->updateInvoices();

        $this->updateBills();

        $this->updateModules();

        $this->updatePermissions();

        $this->deleteOldFiles();

        $this->updateEnv();
    }

    public function updateDatabase()
    {
        DB::table('migrations')->insert([
            'id' => DB::table('migrations')->max('id') + 1,
            'migration' => '2017_09_14_000000_core_v1',
            'batch' => DB::table('migrations')->max('batch') + 1,
        ]);

        Artisan::call('migrate', ['--force' => true]);
    }

    protected function updateCompanies()
    {
        $company_id = session('company_id');

        $companies = Company::cursor();

        foreach ($companies as $company) {
            session(['company_id' => $company->id]);

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
        // Set the active company settings
        setting()->setExtraColumns(['company_id' => $company->id]);
        setting()->forgetAll();
        setting()->load(true);

        // Override settings
        config(['app.url' => url('/')]);
        config(['app.timezone' => setting('general.timezone', 'UTC')]);
        date_default_timezone_set(config('app.timezone'));
        app()->setLocale(setting('general.default_locale'));

        $updated_settings = [
            'company.name'                      => 'general.company_name',
            'company.email'                     => 'general.company_email',
            'company.address'                   => 'general.company_address',
            'company.logo'                      => 'general.company_logo',
            'company.phone'                     => 'general.company_phone',
            'company.tax_number'                => 'general.company_tax_number',
            'localisation.financial_start'      => 'general.financial_start',
            'localisation.timezone'             => 'general.timezone',
            'localisation.date_format'          => 'general.date_format',
            'localisation.date_separator'       => 'general.date_separator',
            'localisation.percent_position'     => 'general.percent_position',
            'invoice.number_prefix'             => 'general.invoice_number_prefix',
            'invoice.number_digit'              => 'general.invoice_number_digit',
            'invoice.number_next'               => 'general.invoice_number_next',
            'invoice.item_name'                 => 'general.invoice_item',
            'invoice.item_input'                => 'general.invoice_item_input',
            'invoice.price_name'                => 'general.invoice_price',
            'invoice.price_input'               => 'general.invoice_price_input',
            'invoice.quantity_name'             => 'general.invoice_quantity',
            'invoice.quantity_input'            => 'general.invoice_quantity_input',
            'invoice.title'                     => trans_choice('general.invoices', 1),
            'invoice.payment_terms'             => '0',
            'invoice.template'                  => 'default',
            'invoice.color'                     => '#55588b',
            'default.account'                   => 'general.default_account',
            'default.currency'                  => 'general.default_currency',
            'default.tax'                       => 'general.default_tax',
            'default.locale'                    => 'general.default_locale',
            'default.list_limit'                => 'general.list_limit',
            'default.payment_method'            => 'general.default_payment_method',
            'default.use_gravatar'              => 'general.use_gravatar',
            'email.protocol'                    => 'general.email_protocol',
            'email.sendmail_path'               => 'general.email_sendmail_path',
            'email.smtp_host'                   => 'general.email_smtp_host',
            'email.smtp_port'                   => 'general.email_smtp_port',
            'email.smtp_username'               => 'general.email_smtp_username',
            'email.smtp_password'               => 'general.email_smtp_password',
            'email.smtp_encryption'             => 'general.email_smtp_encryption',
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
                    $value = str_replace('offlinepayment.', 'offline-payments.', setting($old, 'missing_old_setting_value'));

                    break;
                case 'invoice.title':
                case 'invoice.payment_terms':
                case 'invoice.template':
                case 'invoice.color':
                case 'contact.type.customer':
                case 'contact.type.vendor':
                    $value = $old;

                    break;
                default:
                    $value = setting($old, 'missing_old_setting_value');

                    break;
            }

            if ($value == 'missing_old_setting_value') {
                continue;
            }

            setting()->set([$new => $value]);
            setting()->forget($old);
        }

        if ($invoice_logo = setting('general.invoice_logo')) {
            setting()->set(['company.logo' => $invoice_logo]);
        }

        $removed_settings = [
            'general.admin_theme',
            'general.session_handler',
            'general.session_lifetime',
            'general.file_size',
            'general.file_types',
            'general.send_item_reminder',
            'general.schedule_item_stocks',
            'general.invoice_prefix',
            'general.invoice_digit',
            'general.invoice_start',
            'general.invoice_logo',
        ];

        foreach ($removed_settings as $removed_setting) {
            setting()->forget($removed_setting);
        }

        setting()->save();
    }

    public function createEmailTemplates($company)
    {
        Artisan::call('company:seed', [
            'company' => $company->id,
            '--class' => 'Database\Seeds\EmailTemplates',
        ]);
    }

    public function createReports($company)
    {
        Artisan::call('company:seed', [
            'company' => $company->id,
            '--class' => 'Database\Seeds\Reports',
        ]);
    }

    public function createDashboards()
    {
        $users = User::cursor();

        foreach ($users as $user) {
            $companies = $user->companies;

            foreach ($companies as $company) {
                app()->setLocale($company->locale);

                Artisan::call('user:seed', [
                    'user' => $user->id,
                    'company' => $company->id,
                ]);
            }
        }
    }

    public function copyContacts()
    {
        $this->copyCustomers();

        $this->copyVendors();
    }

    public function copyCustomers()
    {
        $customers = DB::table('customers')->cursor();

        $has_estimates = Schema::hasTable('estimates');
        $has_crm_companies = Schema::hasTable('crm_companies');
        $has_crm_contacts = Schema::hasTable('crm_contacts');
        $has_idea_soft_histories = Schema::hasTable('idea_soft_histories');
        $has_custom_fields_field_values = Schema::hasTable('custom_fields_field_values');

        foreach ($customers as $customer) {
            $data = (array) $customer;
            $data['type'] = 'customer';
            unset($data['id']);

            $contact_id = DB::table('contacts')->insertGetId($data);

            DB::table('invoices')
                ->where('contact_id', $customer->id)
                ->update([
                    'contact_id' => $contact_id,
                ]);

            DB::table('revenues')
                ->where('customer_id', $customer->id)
                ->update([
                    'customer_id' => $contact_id,
                ]);

            if ($has_estimates) {
                DB::table('estimates')
                    ->where('customer_id', $customer->id)
                    ->update([
                        'customer_id' => $contact_id,
                    ]);
            }

            if ($has_crm_companies) {
                DB::table('crm_companies')
                    ->where('core_customer_id', $customer->id)
                    ->update([
                        'core_customer_id' => $contact_id,
                    ]);
            }

            if ($has_crm_contacts) {
                DB::table('crm_contacts')
                    ->where('core_customer_id', $customer->id)
                    ->update([
                        'core_customer_id' => $contact_id,
                    ]);
            }

            if ($has_idea_soft_histories) {
                DB::table('idea_soft_histories')
                    ->where('model_id', $customer->id)
                    ->where('model_type', 'App\Models\Income\Customer')
                    ->update([
                        'model_id' => $contact_id,
                        'model_type' => 'App\Models\Common\Contact',
                    ]);
            }

            if ($has_custom_fields_field_values) {
                DB::table('custom_fields_field_values')
                    ->where('model_id', $customer->id)
                    ->where('model_type', 'App\Models\Income\Customer')
                    ->update([
                        'model_id' => $contact_id,
                        'model_type' => 'App\Models\Common\Contact',
                    ]);
            }
        }

        Schema::drop('customers');
    }

    public function copyVendors()
    {
        $vendors = DB::table('vendors')->cursor();

        $has_custom_fields_field_values = Schema::hasTable('custom_fields_field_values');

        foreach ($vendors as $vendor) {
            $data = (array) $vendor;
            $data['type'] = 'vendor';
            unset($data['id']);

            $contact_id = DB::table('contacts')->insertGetId($data);

            DB::table('bills')
                ->where('contact_id', $vendor->id)
                ->update([
                    'contact_id' => $contact_id,
                ]);

            DB::table('payments')
                ->where('vendor_id', $vendor->id)
                ->update([
                    'vendor_id' => $contact_id,
                ]);

            DB::table('mediables')
                ->where('mediable_id', $vendor->id)
                ->where('mediable_type', 'App\Models\Expense\Vendor')
                ->update([
                    'mediable_id' => $contact_id,
                    'mediable_type' => 'App\Models\Common\Contact',
                ]);

            if ($has_custom_fields_field_values) {
                DB::table('custom_fields_field_values')
                    ->where('model_id', $vendor->id)
                    ->where('model_type', 'App\Models\Expense\Vendor')
                    ->update([
                        'model_id' => $contact_id,
                        'model_type' => 'App\Models\Common\Contact',
                    ]);
            }
        }

        Schema::drop('vendors');
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

        $has_double_entry_ledger = Schema::hasTable('double_entry_ledger');

        foreach ($invoice_payments as $invoice_payment) {
            $invoice = DB::table('invoices')->where('id', $invoice_payment->invoice_id)->first();

            if (empty($invoice)) {
                continue;
            }

            $payment_method = str_replace('offlinepayment.', 'offline-payments.', $invoice_payment->payment_method);

            $transaction_id = DB::table('transactions')->insertGetId([
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

            if ($has_double_entry_ledger) {
                DB::table('double_entry_ledger')
                    ->where('ledgerable_id', $invoice_payment->id)
                    ->where('ledgerable_type', 'App\Models\Income\InvoicePayment')
                    ->update([
                        'ledgerable_id' => $transaction_id,
                        'ledgerable_type' => 'App\Models\Banking\Transaction',
                    ]);
            }
        }

        Schema::drop('invoice_payments');
    }

    public function copyRevenues()
    {
        $revenues = DB::table('revenues')->cursor();

        $has_double_entry_ledger = Schema::hasTable('double_entry_ledger');
        $has_project_revenues = Schema::hasTable('project_revenues');
        $has_custom_fields_field_values = Schema::hasTable('custom_fields_field_values');

        foreach ($revenues as $revenue) {
            $payment_method = str_replace('offlinepayment.', 'offline-payments.', $revenue->payment_method);

            $transaction_id = DB::table('transactions')->insertGetId([
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
                'payment_method' => $payment_method,
                'reference' => $revenue->reference,
                'parent_id' => $revenue->parent_id,
                'reconciled' => $revenue->reconciled,
                'created_at' => $revenue->created_at,
                'updated_at' => $revenue->updated_at,
                'deleted_at' => $revenue->deleted_at,
            ]);

            DB::table('transfers')
                ->where('income_transaction_id', $revenue->id)
                ->update([
                    'income_transaction_id' => $transaction_id,
                ]);

            DB::table('recurring')
                ->where('recurable_id', $revenue->id)
                ->where('recurable_type', 'App\Models\Income\Revenue')
                ->update([
                    'recurable_id' => $transaction_id,
                    'recurable_type' => 'App\Models\Banking\Transaction',
                ]);

            DB::table('mediables')
                ->where('mediable_id', $revenue->id)
                ->where('mediable_type', 'App\Models\Income\Revenue')
                ->update([
                    'mediable_id' => $transaction_id,
                    'mediable_type' => 'App\Models\Banking\Transaction',
                ]);

            if ($has_double_entry_ledger) {
                DB::table('double_entry_ledger')
                    ->where('ledgerable_id', $revenue->id)
                    ->where('ledgerable_type', 'App\Models\Income\Revenue')
                    ->update([
                        'ledgerable_id' => $transaction_id,
                        'ledgerable_type' => 'App\Models\Banking\Transaction',
                    ]);
            }

            if ($has_project_revenues) {
                DB::table('project_revenues')
                    ->where('revenue_id', $revenue->id)
                    ->update([
                        'revenue_id' => $transaction_id,
                    ]);
            }

            if ($has_custom_fields_field_values) {
                DB::table('custom_fields_field_values')
                    ->where('model_id', $revenue->id)
                    ->where('model_type', 'App\Models\Income\Revenue')
                    ->update([
                        'model_id' => $transaction_id,
                        'model_type' => 'App\Models\Banking\Transaction',
                    ]);
            }
        }

        Schema::drop('revenues');
    }

    public function copyBillPayments()
    {
        $bill_payments = DB::table('bill_payments')->cursor();

        $has_double_entry_ledger = Schema::hasTable('double_entry_ledger');

        foreach ($bill_payments as $bill_payment) {
            $bill = DB::table('bills')->where('id', $bill_payment->bill_id)->first();

            if (empty($bill)) {
                continue;
            }

            $payment_method = str_replace('offlinepayment.', 'offline-payments.', $bill_payment->payment_method);

            $transaction_id = DB::table('transactions')->insertGetId([
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

            if ($has_double_entry_ledger) {
                DB::table('double_entry_ledger')
                    ->where('ledgerable_id', $bill_payment->id)
                    ->where('ledgerable_type', 'App\Models\Expense\BillPayment')
                    ->update([
                        'ledgerable_id' => $transaction_id,
                        'ledgerable_type' => 'App\Models\Banking\Transaction',
                    ]);
            }
        }

        Schema::drop('bill_payments');
    }

    public function copyPayments()
    {
        $payments = DB::table('payments')->cursor();

        $has_double_entry_ledger = Schema::hasTable('double_entry_ledger');
        $has_project_payments = Schema::hasTable('project_payments');
        $has_receipts = Schema::hasTable('receipts');
        $has_custom_fields_field_values = Schema::hasTable('custom_fields_field_values');

        foreach ($payments as $payment) {
            $payment_method = str_replace('offlinepayment.', 'offline-payments.', $payment->payment_method);

            $transaction_id = DB::table('transactions')->insertGetId([
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
                'payment_method' => $payment_method,
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
                    'expense_transaction_id' => $transaction_id,
                ]);

            DB::table('recurring')
                ->where('recurable_id', $payment->id)
                ->where('recurable_type', 'App\Models\Expense\Payment')
                ->update([
                    'recurable_id' => $transaction_id,
                    'recurable_type' => 'App\Models\Banking\Transaction',
                ]);

            DB::table('mediables')
                ->where('mediable_id', $payment->id)
                ->where('mediable_type', 'App\Models\Expense\Payment')
                ->update([
                    'mediable_id' => $transaction_id,
                    'mediable_type' => 'App\Models\Banking\Transaction',
                ]);

            if ($has_double_entry_ledger) {
                DB::table('double_entry_ledger')
                    ->where('ledgerable_id', $payment->id)
                    ->where('ledgerable_type', 'App\Models\Expense\Payment')
                    ->update([
                        'ledgerable_id' => $transaction_id,
                        'ledgerable_type' => 'App\Models\Banking\Transaction',
                    ]);
            }

            if ($has_project_payments) {
                DB::table('project_payments')
                    ->where('payment_id', $payment->id)
                    ->update([
                        'payment_id' => $transaction_id,
                    ]);
            }

            if ($has_receipts) {
                DB::table('receipts')
                    ->where('payment_id', $payment->id)
                    ->update([
                        'payment_id' => $transaction_id,
                    ]);
            }

            if ($has_custom_fields_field_values) {
                DB::table('custom_fields_field_values')
                    ->where('model_id', $payment->id)
                    ->where('model_type', 'App\Models\Expense\Payment')
                    ->update([
                        'model_id' => $transaction_id,
                        'model_type' => 'App\Models\Banking\Transaction',
                    ]);
            }
        }

        Schema::drop('payments');
    }

    public function updateInvoices()
    {
        DB::table('recurring')
            ->where('recurable_type', 'App\Models\Income\Invoice')
            ->update([
                'recurable_type' => 'App\Models\Sale\Invoice',
            ]);

        DB::table('mediables')
            ->where('mediable_type', 'App\Models\Income\Invoice')
            ->update([
                'mediable_type' => 'App\Models\Sale\Invoice',
            ]);
    }

    public function updateBills()
    {
        DB::table('recurring')
            ->where('recurable_type', 'App\Models\Expense\Bill')
            ->update([
                'recurable_type' => 'App\Models\Purchase\Bill',
            ]);

        DB::table('mediables')
            ->where('mediable_type', 'App\Models\Expense\Bill')
            ->update([
                'mediable_type' => 'App\Models\Purchase\Bill',
            ]);
    }

    public function updateModules()
    {
        DB::table('modules')
            ->where('alias', 'offlinepayment')
            ->update([
                'alias' => 'offline-payments',
            ]);

        DB::table('modules')
            ->where('alias', 'paypalstandard')
            ->update([
                'alias' => 'paypal-standard',
            ]);
    }

    public function updatePermissions()
    {
        $this->attachPermissionsByRoleNames([
            'admin' => [
                'common-dashboards' => 'c,r,u,d',
                'common-reports' => 'c,r,u,d',
                'common-search' => 'r',
                'common-widgets' => 'c,r,u,d',
                'modules-api-key' => 'c,u',
                'offline-payments-settings' => 'r,u,d',
                'paypal-standard-settings' => 'r,u',
                'settings-company' => 'r',
                'settings-defaults' => 'r',
                'settings-email' => 'r',
                'settings-invoice' => 'r',
                'settings-localisation' => 'r',
                'settings-modules' => 'r,u',
                'settings-schedule' => 'r',
                'widgets-account-balance' => 'r',
                'widgets-cash-flow' => 'r',
                'widgets-expenses-by-category' => 'r',
                'widgets-income-by-category' => 'r',
                'widgets-latest-expenses' => 'r',
                'widgets-latest-income' => 'r',
                'widgets-total-expenses' => 'r',
                'widgets-total-income' => 'r',
                'widgets-total-profit' => 'r',
            ],
            'manager' => [
                'common-dashboards' => 'c,r,u,d',
                'common-reports' => 'c,r,u,d',
                'common-search' => 'r',
                'common-widgets' => 'c,r,u,d',
                'offline-payments-settings' => 'r,u,d',
                'paypal-standard-settings' => 'r,u',
                'settings-company' => 'r',
                'settings-defaults' => 'r',
                'settings-email' => 'r',
                'settings-invoice' => 'r',
                'settings-localisation' => 'r',
                'settings-modules' => 'r,u',
                'settings-schedule' => 'r',
                'widgets-account-balance' => 'r',
                'widgets-cash-flow' => 'r',
                'widgets-expenses-by-category' => 'r',
                'widgets-income-by-category' => 'r',
                'widgets-latest-expenses' => 'r',
                'widgets-latest-income' => 'r',
                'widgets-total-expenses' => 'r',
                'widgets-total-income' => 'r',
                'widgets-total-profit' => 'r',
            ],
            'customer' => [
                'client-portal' => 'r',
                'portal-invoices' => 'r,u',
                'portal-payments' => 'r,u',
                'portal-profile' => 'r,u',
            ],
        ]);

        $this->detachPermissionsByRoleNames([
            'admin' => [
                'read-modules-token',
                'update-modules-token',
                'create-wizard-companies',
                'read-wizard-companies',
                'update-wizard-companies',
                'create-wizard-currencies',
                'read-wizard-currencies',
                'update-wizard-currencies',
                'delete-wizard-currencies',
                'create-wizard-taxes',
                'read-wizard-taxes',
                'update-wizard-taxes',
                'create-wizard-finish',
                'read-wizard-finish',
                'update-wizard-finish',
            ],
            'manager' => [
                'read-modules-token',
                'update-modules-token',
                'create-wizard-companies',
                'read-wizard-companies',
                'update-wizard-companies',
                'create-wizard-currencies',
                'read-wizard-currencies',
                'update-wizard-currencies',
                'delete-wizard-currencies',
                'create-wizard-taxes',
                'read-wizard-taxes',
                'update-wizard-taxes',
                'create-wizard-finish',
                'read-wizard-finish',
                'update-wizard-finish',
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

        $this->updatePermissionNames([
            'expenses-bills' => 'purchases-bills',
            'expenses-payments' => 'purchases-payments',
            'expenses-vendors' => 'purchases-vendors',
            'incomes-customers' => 'sales-customers',
            'incomes-invoices' => 'sales-invoices',
            'incomes-revenues' => 'sales-revenues',
        ]);
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
            'app/Http/Controllers/Common/Dashboard.php',
            'app/Http/Controllers/Modals/BillPayments.php',
            'app/Http/Controllers/Modals/InvoicePayments.php',
            'app/Http/Controllers/modules/Token.php',
            'app/Http/Middleware/CustomerMenu.php',
            'app/Http/Middleware/RedirectIfWizardCompleted.php',
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
            'database/seeds/Roles.php',
            'database/seeds/CompanySeeder.php',
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
            'resources/views/purchases/bills/bill.blade.php',
            'resources/views/sales/invoices/invoice.blade.php',
            'resources/views/layouts/bill.blade.php',
            'resources/views/layouts/customer.blade.php',
            'resources/views/layouts/invoice.blade.php',
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
            'app/Http/Controllers/Api/Expenses',
            'app/Http/Controllers/Api/Incomes',
            'app/Http/Controllers/Expenses',
            'app/Http/Controllers/Incomes',
            'app/Http/Controllers/Customers',
            'app/Http/Controllers/Reports',
            'app/Http/Requests/Customer',
            'app/Http/Requests/Expense',
            'app/Http/Requests/Income',
            'app/Jobs/Expense',
            'app/Jobs/Income',
            'app/Listeners/Incomes',
            'app/Listeners/Updates',
            'app/Models/Company',
            'app/Models/Expense',
            'app/Models/Income',
            'app/Models/Item',
            'app/Notifications/Expense',
            'app/Notifications/Income',
            'app/Overrides',
            'app/Transformers/Expense',
            'app/Transformers/Income',
            'modules/OfflinePayment',
            'public/js/chartjs',
            'public/js/daterangepicker',
            'public/js/highchart',
            'public/js/lightbox',
            'public/js/moment',
            'resources/views/common/dashboard',
            'resources/views/customers',
            'resources/views/expenses',
            'resources/views/incomes',
            'resources/views/partials/customer',
            'resources/views/reports/expense_summary',
            'resources/views/reports/income_expense_summary',
            'resources/views/reports/income_summary',
            'tests/Feature/Expenses',
            'tests/Feature/Incomes',
            'tests/Feature/Reports',
        ];

        foreach ($files as $file) {
            File::delete(base_path($file));
        }

        foreach ($directories as $directory) {
            File::deleteDirectory(base_path($directory));
        }
    }

    public function updateEnv()
    {
        Installer::updateEnv([
            'QUEUE_CONNECTION'      =>  'sync',
            'LOG_CHANNEL'           =>  'stack',
            'FIREWALL_ENABLED'      =>  'true',
            'MODEL_CACHE_ENABLED'   =>  'true',
        ]);
    }
}
