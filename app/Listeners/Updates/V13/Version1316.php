<?php

namespace App\Listeners\Updates\V13;

use App\Events\UpdateFinished;
use App\Listeners\Updates\Listener;
use App\Models\Common\Company;
use App\Utilities\Overrider;
use App\Models\Banking\Account;
use Artisan;
use Date;

class Version1316 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '1.3.16';

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(UpdateFinished $event)
    {
        // Check if should listen
        if (!$this->check($event)) {
            return;
        }

        // Cache Clear
        Artisan::call('cache:clear');

        $this->setSettings();

        // Update database
        Artisan::call('migrate', ['--force' => true]);
    }

    protected function setSettings()
    {
        $company_id = session('company_id');

        // Create new bill statuses
        $companies = Company::all();

        foreach ($companies as $company) {
            // Set settings
            setting()->forgetAll();

            session(['company_id' => $company->id]);

            Overrider::load('settings');

            $settings = [
                'general.financial_start'           => Date::now()->startOfYear()->format('d-m'),
                'general.timezone'                  => 'Europe/London',
                'general.date_format'               => 'd M Y',
                'general.date_separator'            => 'space',
                'general.percent_position'          => 'after',
                'general.invoice_number_prefix'     => 'INV-',
                'general.invoice_number_digit'      => '5',
                'general.invoice_number_next'       => '1',
                'general.default_payment_method'    => 'offlinepayment.cash.1',
                'general.email_protocol'            => 'mail',
                'general.email_sendmail_path'       => '/usr/sbin/sendmail -bs',
                'general.send_invoice_reminder'     => '0',
                'general.schedule_invoice_days'     => '1,3,5,10',
                'general.send_bill_reminder'        => '0',
                'general.schedule_bill_days'        => '10,5,3,1',
                'general.send_item_reminder'        => '0',
                'general.schedule_item_stocks'      => '3,5,7',
                'general.schedule_time'             => '09:00',
                'general.admin_theme'               => 'skin-green-light',
                'general.list_limit'                => '25',
                'general.use_gravatar'              => '0',
                'general.session_handler'           => 'file',
                'general.session_lifetime'          => '30',
                'general.file_size'                 => '2',
                'general.file_types'                => 'pdf,jpeg,jpg,png',
                'general.wizard'                    => '0',
                'general.invoice_item'              => 'settings.invoice.item',
                'general.invoice_price'             => 'settings.invoice.price',
                'general.invoice_quantity'          => 'settings.invoice.quantity',
                'offlinepayment.methods'            => '[{"code":"offlinepayment.cash.1","name":"Cash","order":"1","description":null},{"code":"offlinepayment.bank_transfer.2","name":"Bank Transfer","order":"2","description":null}]',
            ];

            foreach ($settings as $key => $value) {
                if (!empty(setting($key))) {
                    continue;
                }

                setting([$key => $value]);
            }

            if (empty(setting('general.default_account'))) {
                $account = Account::where('company_id', $company->id)->first();

                if ($account) {
                    setting()->set('general.default_account', $account->id);
                }
            }

            setting()->save();
        }

        setting()->forgetAll();

        session(['company_id' => $company_id]);

        Overrider::load('settings');
    }
}
