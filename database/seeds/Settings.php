<?php

namespace Database\Seeds;

use App\Abstracts\Model;
use Illuminate\Database\Seeder;

class Settings extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->create();

        Model::reguard();
    }

    private function create()
    {
        $company_id = $this->command->argument('company');

        setting()->setExtraColumns(['company_id' => $company_id]);

        $offline_payments = [];

        $offline_payments[] = [
            'code' => 'offline-payments.cash.1',
            'name' => trans('demo.offline_payments.cash'),
            'customer' => '0',
            'order' => '1',
            'description' => null,
        ];

        $offline_payments[] = [
            'code' => 'offline-payments.bank_transfer.2',
            'name' => trans('demo.offline_payments.bank'),
            'customer' => '0',
            'order' => '2',
            'description' => null,
        ];

        setting()->set([
            'localisation.financial_start'      => now()->startOfYear()->format('d-m'),
            'localisation.timezone'             => 'Europe/London',
            'localisation.date_format'          => 'd M Y',
            'localisation.date_separator'       => 'space',
            'localisation.percent_position'     => 'after',
            'invoice.number_prefix'             => 'INV-',
            'invoice.number_digit'              => '5',
            'invoice.number_next'               => '1',
            'invoice.item_name'                 => 'settings.invoice.item',
            'invoice.price_name'                => 'settings.invoice.price',
            'invoice.quantity_name'             => 'settings.invoice.quantity',
            'invoice.title'                     => trans_choice('general.invoices', 1),
            'invoice.payment_terms'             => '0',
            'invoice.template'                  => 'default',
            'invoice.color'                     => '#55588b',
            'default.payment_method'            => 'offline-payments.cash.1',
            'default.list_limit'                => '25',
            'default.use_gravatar'              => '0',
            'email.protocol'                    => 'mail',
            'email.sendmail_path'               => '/usr/sbin/sendmail -bs',
            'schedule.send_invoice_reminder'    => '0',
            'schedule.invoice_days'             => '1,3,5,10',
            'schedule.send_bill_reminder'       => '0',
            'schedule.bill_days'                => '10,5,3,1',
            'schedule.time'                     => '09:00',
            'wizard.completed'                  => '0',
            'offline-payments.methods'          => json_encode($offline_payments),
            'contact.type.customer'             => 'customer',
            'contact.type.vendor'               => 'vendor',
        ]);
    }
}
