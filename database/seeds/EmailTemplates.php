<?php

namespace Database\Seeds;

use App\Abstracts\Model;
use App\Jobs\Setting\CreateEmailTemplate;
use App\Traits\Jobs;
use Illuminate\Database\Seeder;

class EmailTemplates extends Seeder
{
    use Jobs;

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

        $templates = [
            [
                'alias' => 'invoice_new_customer',
                'class' => 'App\Notifications\Sale\Invoice',
                'name' => 'settings.email.templates.invoice_new_customer',
            ],
            [
                'alias' => 'invoice_remind_customer',
                'class' => 'App\Notifications\Sale\Invoice',
                'name' => 'settings.email.templates.invoice_remind_customer',
            ],
            [
                'alias' => 'invoice_remind_admin',
                'class' => 'App\Notifications\Sale\Invoice',
                'name' => 'settings.email.templates.invoice_remind_admin',
            ],
            [
                'alias' => 'invoice_recur_customer',
                'class' => 'App\Notifications\Sale\Invoice',
                'name' => 'settings.email.templates.invoice_recur_customer',
            ],
            [
                'alias' => 'invoice_recur_admin',
                'class' => 'App\Notifications\Sale\Invoice',
                'name' => 'settings.email.templates.invoice_recur_admin',
            ],
            [
                'alias' => 'invoice_view_admin',
                'class' => 'App\Notifications\Sale\Invoice',
                'name' => 'settings.email.templates.invoice_view_admin',
            ],
            [
                'alias' => 'invoice_payment_customer',
                'class' => 'App\Notifications\Portal\PaymentReceived',
                'name' => 'settings.email.templates.invoice_payment_customer',
            ],
            [
                'alias' => 'invoice_payment_admin',
                'class' => 'App\Notifications\Portal\PaymentReceived',
                'name' => 'settings.email.templates.invoice_payment_admin',
            ],
            [
                'alias' => 'bill_remind_admin',
                'class' => 'App\Notifications\Purchase\Bill',
                'name' => 'settings.email.templates.bill_remind_admin',
            ],
            [
                'alias' => 'bill_recur_admin',
                'class' => 'App\Notifications\Purchase\Bill',
                'name' => 'settings.email.templates.bill_recur_admin',
            ],
            [
                'alias' => 'payment_received_customer',
                'class' => 'App\Notifications\Banking\Transaction',
                'name' => 'settings.email.templates.payment_received_customer',
            ],
            [
                'alias' => 'payment_made_vendor',
                'class' => 'App\Notifications\Banking\Transaction',
                'name' => 'settings.email.templates.payment_made_vendor',
            ],
        ];

        foreach ($templates as $template) {
            $this->dispatch(new CreateEmailTemplate([
                'company_id' => $company_id,
                'alias' => $template['alias'],
                'class' => $template['class'],
                'name' => $template['name'],
                'subject' => trans('email_templates.' . $template['alias'] . '.subject'),
                'body' => trans('email_templates.' . $template['alias'] . '.body'),
                'created_from' => 'core::seed',
            ]));
        }
    }
}
